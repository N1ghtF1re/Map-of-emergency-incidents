unit Unit1;
//
// Sujets : Supprimer le scintillement avec la fonction DoubleBuffered
//          Utilisation de TBitMap.ScanLine pour un dessin rapide
//          Intégration de l'assembleur dans un programme Delphi.
//
// Par Nono40 : http://nono40.developpez.com, http://nono40.fr.st
//              mailto:nono40@fr.st
//
// Le 23/03/2003

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, ExtCtrls, ComCtrls;

type
  TForm1 = class(TForm)
    Image1: TImage;
    Image2: TImage;
    Bevel1: TBevel;
    Bevel2: TBevel;
    Panel1: TPanel;
    Curseur2D: TImage;
    Curseur2G: TImage;
    Curseur1D: TImage;
    Curseur1B: TImage;
    Curseur1G: TImage;
    Curseur1H: TImage;
    lRouge: TLabel;
    lVert: TLabel;
    lBleu: TLabel;
    CheckBox1: TCheckBox;
    Viseur: TImage;
    procedure FormCreate(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
    procedure Image1MouseMove(Sender: TObject; Shift: TShiftState; X,
      Y: Integer);
    procedure Image2MouseMove(Sender: TObject; Shift: TShiftState; X,
      Y: Integer);
    procedure Image1MouseDown(Sender: TObject; Button: TMouseButton;
      Shift: TShiftState; X, Y: Integer);
    procedure Image2MouseDown(Sender: TObject; Button: TMouseButton;
      Shift: TShiftState; X, Y: Integer);
    procedure CheckBox1Click(Sender: TObject);
  private
    { Déclarations privées }
    // Contient l'image principale de sélection de la couleur de bas
    Image:TBitMap;
    // Contient l'image de la bande de sélection de la luminosité.
    Bande:TBitMap;
  public
    { Déclarations publiques }
    Procedure MAJBande(Couleur:TColor);
    Procedure MAJPanneau;
  end;

var
  Form1: TForm1;

implementation

{$R *.dfm}

procedure TForm1.FormCreate(Sender: TObject);
Const
// Le tableau points contients les points de couleurs qui seront dégradés
// dans l'image de base ( Rouge - Jaune - Vert - Cyan - Bleu - Violet - Rouge )
  Points:Array[0..6]Of Array[1..3]Of Integer=
      (($00,$00,$FF),($00,$FF,$FF),($00,$FF,$00),($FF,$FF,$00),
       ($FF,$00,$00),($FF,$00,$FF),($00,$00,$FF));
Var P : Pointer;
begin
  // Création du bit map principal
  Image:=TBitMap.Create;
  Image.Width       := 64*6;
  Image.Height      := 256+1;
  Image.PixelFormat := pf32Bit;
  P                 := Image.ScanLine[0];
  Asm
    // Il faut par principe sauvegarder ces trois registres
    // car ils sont utilisés par Delphi
    PUSH EBX
    PUSH EDI
    PUSH ESI

    // EDI va pointer dans le bit map
    MOV  EDI,P
    // ESI va poointer dans le tableau Points
    XOR  ESI,ESI

      // Début de la boucle principale qui sera effectuée trois fois
      // un fois pour le bleu puis le vert en enfin le rouge
@LD:  PUSH EDI
      PUSH ESI

@L2:    XOR  ECX,ECX

@L1:      // On calcul d'abord dans EBX la valeur du haut de l'image
          // pour la couleur en court ( B V ou R ). C'est une moyenne
          // pondérée entre les deux points du tableau Points.
          MOV  EBX,DWord ptr Points[ESI+12]
          SUB  EBX,DWord ptr Points[ESI]
          IMUL EBX,ECX
          SHR  EBX,6
          ADD  EBX,DWord ptr Points[ESI]

          // On prépare ensuite le calcul des points situés en dessous
          PUSH EDI
          XOR  EDX,EDX

@LA:        // Les points en dessous sont une moyenne pondérée entre le
            // point du haut et le gris moyen ( 128 , 128 , 128 )
            MOV  EAX,128
            SUB  EAX,EBX
            IMUL EAX,EDX
            SHR  EAX,8
            ADD  EAX,EBX

            // Le point est stocké dans le BitMap
            MOV  BYTE PTR [EDI],AL
            // Pour passer au point en dessous il faut diminuer EDI
            // car les lignes d'un BitMap sont stockées à l'envers
            SUB  EDI,64*6*4

            // On continue pour la hauteur de l'image
            INC  EDX
            CMP  EDX,256
            JBE  @LA

          // EDI est remis en haut de l'image
          POP  EDI
          // puis sur le point juste à gauche
          ADD  EDI,4
          // Il faut refaire le calcul pour les 64 points de dégradés
          // répartis entre deux points du tableau Points
          INC  ECX
          CMP  ECX,64
          JB   @L1

        // Ensuite il faut passer sur le point suivant du tableau Points
        ADD  ESI,12
        CMP  ESI,72
        JB   @L2

      // Enfin il faut revenir au début de l'image pour effectuer
      // les autres couleurs primaires
      POP  ESI
      POP  EDI
      INC  EDI
      ADD  ESI,4
      CMP  ESI,12
      JB   @LD

    // Il faut rendre à Delphi ce qui appartient à Delphi
    POP  ESI
    POP  EDI
    POP  EBX
  End;
  Image1.Picture.Assign(Image);

  // Création de l'image servant au dessin de la bande
  Bande:=TBitMap.Create;
  Bande.Width       := 16;
  Bande.Height      := 256+1;
  Bande.PixelFormat := pf32Bit;

  // Mise à jour de la Bande et mise en place des curseurs
  Image1MouseMove(Nil,[ssLeft],128,128);
  Image2MouseMove(Nil,[ssLeft],0  ,128);
  CheckBox1Click(Nil);
end;

procedure TForm1.FormDestroy(Sender: TObject);
begin
  // Ne pas oublier de libérer les composants non visuels
  Image.Free;
  Bande.Free;
end;

procedure TForm1.MAJBande(Couleur: TColor);
Var
  P : Pointer;
begin
  P := Bande.ScanLine[0];

  // Mise à jour du BitMap de la bande
  // Le principe est le même que le dessin de base
  Asm
    PUSH EBX
    PUSH EDI
    PUSH ESI

    MOV  EDI,P
    MOV  EDX,3

@L2:  PUSH EDI
      XOR  ECX,ECX
@L1:    XOR  EAX,EAX
        MOV  AL,Byte Ptr Couleur+2
        SUB  EAX,255
        IMUL EAX,ECX
        SHR  EAX,7
        ADD  EAX,255

        XOR  EBX,EBX
        MOV  BL,Byte Ptr Couleur+2
        NEG  EBX
        IMUL EBX,ECX
        SHR  EBX,7
        ADD  BL,Byte Ptr Couleur+2

        MOV  ESI,16
@LL:    DEC  ESI
        MOV  BYTE PTR [EDI+ESI*4],AL
        MOV  BYTE PTR [EDI+ESI*4-128*16*4],BL
        JNZ  @LL

        SUB  EDI,16*4
        INC  ECX
        CMP  ECX,128
        JBE  @L1

      POP  EDI
      INC  EDI
      SHL  COULEUR,8
      DEC  EDX
      JNZ  @L2

    POP  ESI
    POP  EDI
    POP  EBX
  End;
  Image2.Picture.Assign(Bande);
  MAJPanneau;
end;

procedure TForm1.MAJPanneau;
Var Couleur:TColor;
begin
  // Mise à jour de la couleur choisie
  Couleur      := Image2.Canvas.Pixels[0,Curseur2D.Top - Image2.Top + Curseur2D.Height Div 2];
  Panel1.Color := Couleur;
  // Elle est décomposée en ses trois couleurs de base
  lRouge.Caption := IntToStr((Couleur And $0000FF)      );
  lVert .Caption := IntToStr((Couleur And $00FF00)Shr 8 );
  lBleu .Caption := IntToStr((Couleur And $FF0000)Shr 16);
end;

procedure TForm1.Image1MouseMove(Sender: TObject; Shift: TShiftState; X,
  Y: Integer);
begin
  If   (ssLeft In Shift) Then
  Begin
    // Mise en place des petites images servant de curseur
    // Rien de bien compliqué
    If X<0 Then X:=0;
    If X>=Image1.Width Then X:=Image1.Width-1;
    If Y<0 Then Y:=0;
    If Y>=Image1.Height Then Y:=Image1.Height-1;
    MAJBande(Image1.Canvas.Pixels[x,y]);
    Curseur1D.Top  := Image1.Top  - Curseur1D.Height Div 2 +Y;
    Curseur1G.Top  := Curseur1D.Top + 1;
    Curseur1H.Left := Image1.Left - Curseur1H.Width  Div 2 +X;
    Curseur1B.Left := Curseur1H.Left;
    Viseur.Top     := Curseur1D.Top  - 6;
    Viseur.Left    := Curseur1H.Left - 7;
  End;
end;

procedure TForm1.Image2MouseMove(Sender: TObject; Shift: TShiftState; X,
  Y: Integer);
begin
  If (ssLeft In Shift)Then
  Begin
    // Mise en place des petites images servant de curseur
    // Rien de bien compliqué
    If Y<0 Then Y:=0;
    If Y>=Image2.Height Then Y:=Image2.Height-1;
    Curseur2D.Top := Image2.Top - Curseur2D.Height Div 2 + Y;
    Curseur2G.Top := Curseur2D.Top + 1;
    MAJPanneau;
  End;
end;

procedure TForm1.Image1MouseDown(Sender: TObject; Button: TMouseButton;
  Shift: TShiftState; X, Y: Integer);
begin
  // Sur le OnMouseDown la même chose est faire que le OnMouseMove
  Image1MouseMove(Sender,Shift,x,y);
end;

procedure TForm1.Image2MouseDown(Sender: TObject; Button: TMouseButton;
  Shift: TShiftState; X, Y: Integer);
begin
  // Sur le OnMouseDown la même chose est faire que le OnMouseMove
  Image2MouseMove(Sender,Shift,x,y);
end;

procedure TForm1.CheckBox1Click(Sender: TObject);
begin
  DoubleBuffered:=CheckBox1.Checked;
end;

end.
