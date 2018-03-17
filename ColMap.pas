unit ColMap;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms, Vcl.Dialogs, Vcl.ExtCtrls, HSLUtils, Vcl.StdCtrls, ComObj, CreateBasicColors;
Const
//  n = 17;
  kek = trunc(255*5.78);
//  shift = kek div n;

type

  TSituationRec = Record
    City: string[40];
    TOfPloho: string[2];
  end;


  TForm1 = class(TForm)
    Image1: TImage;
    Memo1: TMemo;
    dlgOpen: TOpenDialog;
    procedure FormCreate(Sender: TObject);

  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  Form1: TForm1;
  MassOfStandart: array of TRecordCust;
  N,shift:Integer;

implementation

{$R *.dfm}

procedure Xls_Open(XLSFile:string; Memo: TMemo);
 const
  xlCellTypeLastCell = $0000000B;
var
  ExlApp, Sheet: OLEVariant;
  i, j, r, c:integer;

begin
  //создаем объект Excel
  ExlApp := CreateOleObject('Excel.Application');

  //делаем окно Excel невидимым
  ExlApp.Visible := false;

  //открываем файл XLSFile
  ExlApp.Workbooks.Open(XLSFile);

  //создаем объект Sheet(страница) и указываем номер листа (1)
  //в книге, с которого будем осуществл€ть чтение
  Sheet := ExlApp.Workbooks[ExtractFileName(XLSFile)].WorkSheets[1];

  //активируем последнюю €чейку на листе
  Sheet.Cells.SpecialCells(xlCellTypeLastCell, EmptyParam).Activate;

    r := ExlApp.ActiveCell.Row;

     for j:= 1 to r do
     begin
         i:= 57+26;
         Memo.Lines.Add(sheet.cells[j,i]);
     end;


 //закрываем приложение Excel
 ExlApp.Quit;

 //очищаем выделенную пам€ть
 ExlApp := Unassigned;
 Sheet := Unassigned;

end;


procedure TForm1.FormCreate(Sender: TObject);
var
  i:integer;
  H,S,L: double;
  r,g,b: integer;
  sum: LongInt;
  Col: TColor;
  Colorik: array of TColor;
  XLSFile: string;
begin
  r:=0;
  g:=0;
  b:=0;

  N:=17;  // CHANGE PLS!!!!!!!!!!!

  {if dlgOpen.Execute then     XLSFile := dlgOpen.FileName;}
  XLSFile := GetCurrentDir + '\kek.xlsx';

  Xls_Open(XLSFile, Memo1);

  SetLength(Colorik,N-1);
  SetLength(MassOfStandart,N-1);
  shift := kek div n;

  creatingBasicColors(MassOfStandart, N, shift);
  for i := 1 to n do
  begin
    //RGBtoHSL(RGB( MassOfStandart[i].red, MassOfStandart[i].green, MassOfStandart[i].blue ), H,S,L);
    //Image1.Canvas.TextOut(i*20, i*20 + 300, FloatToStr(H) + ' ' +  FloatToStr(S) + ' '+ FloatToStr(l));

    Image1.Canvas.Brush.Color := RGB( MassOfStandart[i].red, MassOfStandart[i].green, MassOfStandart[i].blue );
    Image1.Canvas.Rectangle(0+i*20,0,i*20 + 20,200);
    r:= MassOfStandart[i].red;
    g:= MassOfStandart[i].green;
    b:= MassOfStandart[i].blue;

    L := 0.75;
    Col:= RGB( MassOfStandart[i].red, MassOfStandart[i].green, MassOfStandart[i].blue );
    Image1.Canvas.Brush.Color := LighterColor( Col, 67);
    Image1.Canvas.Rectangle(0+i*20,200,i*20 + 20,400);
    //Colorik[i-1] := LighterColor( Col, 67);


    Image1.Canvas.Brush.Color := GrayColor(Col);
    Image1.Canvas.Rectangle(0+i*20,400,i*20 + 20,600);

    {L := 0.95;
    Image1.Canvas.Brush.Color :=
    Image1.Canvas.Rectangle(0+i*20,400,i*20 + 20,600); }


    //ShowMessage( IntToStr(r) + ' ' + IntToStr(G)+ ' ' + IntToStr(B) );
    image1.Canvas.Brush.Color := clwhite;
    //Image1.Canvas.TextOut(i*20, i*20 + 300, IntToStr(MassOfStandart[i].red) + ' ' +  IntToStr(MassOfStandart[i].green) + ' '+ IntToStr(MassOfStandart[i].blue));

  end;


  sum := sum div N;
  Image1.Canvas.Brush.Color := MixColors(Colorik);
  Image1.Canvas.Rectangle(20,600,200,800);
end;

end.

