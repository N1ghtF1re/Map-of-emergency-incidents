unit ColMap;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms,pngimage, Vcl.Dialogs, Vcl.ExtCtrls, HSLUtils,SplashScreen, Vcl.StdCtrls, ComObj, CreateBasicColors;
Const
//  n = 17;
  kek = trunc(255*5.78);
//  shift = kek div n;
type

  TSituationRec = Record
    City: string[40];
    TOfPloho: integer;
  end;
  TSitArr = array of TSituationRec;

  TForm1 = class(TForm)
    Image1: TImage;
    Memo1: TMemo;
    dlgOpen: TOpenDialog;
    introIMG: TImage;
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
  SitArr: TSitArr;
  splash: TSplash;
  maxVal: integer;

implementation

{$R *.dfm}

procedure QuickSort(const size: integer; QA: TSitArr);

procedure Swap(var arr: TSitArr; var el1, el2: Integer);
var tmp:TSituationRec;
begin
  tmp:=arr[el1];
  arr[el1]:=arr[el2];
  arr[el2]:=tmp;
end;
Procedure QSort(L,R: Integer);
var
  I,J,Y:Integer;
  X:String;
begin
  I:=L;
  J:=R;
  X:=QA[(L+R) div 2].City;
  repeat
    while QA[I].City<X do
    begin
      Inc (I);
    end;
    while QA[J].City>X do
    begin
      Dec (J);
    end;
    if I<=J then
    begin
      SWAP(QA,i,j);
      Inc (I);
      Dec (J);
    end;
  until I>J;
  if J>L then
    QSort(L,J);
  if I<R then
    QSort(I,R);
end;
begin
  QSort (0,size);
end;


procedure Xls_Open(XLSFile:string; Memo: TMemo);
 const
  xlCellTypeLastCell = $0000000B;
var
  ExlApp, Sheet: OLEVariant;
  j, r, c:integer;
  CI, SI: integer;
  tmp: string;
begin
  ExlApp := CreateOleObject('Excel.Application');

  ExlApp.Visible := false;

  ExlApp.Workbooks.Open(XLSFile);

  Sheet := ExlApp.Workbooks[ExtractFileName(XLSFile)].WorkSheets[1];

  Sheet.Cells.SpecialCells(xlCellTypeLastCell, EmptyParam).Activate;

  r := ExlApp.ActiveCell.Row;
  SetLength(SitArr, r-2);
  for j:= 2 to r do
  begin
    SI:= 57+26;
    CI:=1;

    SitArr[j-2].City := sheet.cells[j, CI];
    tmp := sheet.cells[j, SI];

    if tmp[2] in ['0'..'9'] then
      SitArr[j-2].TOfPloho := StrToInt( tmp[1] + tmp[2] )
    else
      SitArr[j-2].TOfPloho := StrToInt(tmp[1]);
  end;
  tmp := #0;

 ExlApp.Quit;

 ExlApp := Unassigned;
 Sheet := Unassigned;

end;

function max(a,b:Integer):Integer;
begin
  if A>b then
    result:=a
    else
    result:=b;
end;

procedure nulledArr(size: integer;var Arr: array of Integer);
var i:integer;
begin
  for i := Low(arr) to High(arr) do
    Arr[i] := 0;
end;

function GetMaxVal(sitarr:TSitArr; const N: integer):Integer;
var
  tmpcity:string;
  A:array [1..19]  of Integer;
  i,prmax,currmax,j:Integer;

procedure getM(var currmax: integer;const N:integer;var A: array of integer);
var i:integer;
begin
  for i := Low(a) to High(a) do
   begin
     if currmax<A[i] then
     Currmax:=A[i];
   end;
end;

begin
  tmpcity:=sitarr[1].city;
  i:=0;
  prmax := 0;
  while i < length(SitArr) do
  {for i:= 0 to length(SitArr) - 1 do}
  begin
    nulledArr(N,A);

    while sitarr[i].city = tmpcity do
    begin
    Inc(A[SitArr[i].TOfPloho]);
    inc(i);
    end;
   currmax:=A[1];
   getM(currmax, N, A);
   tmpcity:=sitarr[i].city;
   prmax:=max(prmax,currmax);
  end;
  Result := prmax;
end;


procedure TForm1.FormCreate(Sender: TObject);
var
  i:integer;
  H,S,L: double;
  r,g,b: integer;
  sum: LongInt;
  Col: TColor;
  Colorik: TStringList;
  XLSFile: string;
  currN:integer;
  Rec: TRecordCust;
  HexCol : Cardinal;
  flag: boolean;
  png: TPngImage;
begin
  // SPLASH SCREEN4iK
  png:= TPngImage(introIMG.Picture);
  Splash := TSplash.Create(png);
  //Splash.Show(true);

  r:=0;
  g:=0;
  b:=0;

  N:=19;  // CHANGE PLS!!!!!!!!!!!

  {if dlgOpen.Execute then     XLSFile := dlgOpen.FileName;}
  XLSFile := GetCurrentDir + '\kek.xlsx';

  Xls_Open(XLSFile, Memo1);

  //Memo1.Lines.Add( SitArr[i].City + ' ' + SitArr[i].TOfPloho );


  //SetLength(Colorik,N-1);
  SetLength(MassOfStandart,N-1);
  shift := kek div n;

  creatingBasicColors(MassOfStandart, N, shift);

  currN := 0;
  QuickSort( length(SitArr)-1, SitArr);

  Colorik := TStringList.Create;

  maxVal := GetMaxVal(SitArr, N); // Максимальное значение происшествий в городе
  // ShowMessage( IntToStr(MaxVal) );
  flag := false;
  for i := 0 to length(SitArr) - 1 do
  begin
    if SitArr[i].City = 'Воложинский район' then
    begin
      flag := true;
      //ShowMessage(IntToStr(SitArr[i].TOfPloho-1));
      Rec := MassOfStandart[SitArr[i].TOfPloho-1];
      HexCol := rgb(Rec.green, Rec.red, Rec.blue);
      Colorik.add(IntToStr( HexCol));
      Memo1.Lines.Add(Colorik[currN]);
      inc(currN);
    end
    else if flag then
      break;
  end;
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
  //Splash.Close;
end;

end.

