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


procedure FillMap(var SitArr:TSitArr; Colorik: TStringList; image1: TImage; const Max:integer; Memo: TMemo);
var
  i,j,currN:integer;
  flag: boolean;
  Rec: TRecordCust;
  HexCol : Cardinal;
  SitNumArr: array of integer;
  coef:integer;
begin
  flag := false;
  currN := 0;
  setlength(SitNumArr, N+1);
  for i := Low(SitNumArr) to High(SitNumArr) do
    SitNumArr[i] := 0;

  for i := 0 to length(SitArr) do
  begin
    if SitArr[i].City = 'Минский район' then
    begin
      flag := true;

      inc(SitNumArr[Sitarr[i].TOfPloho]);
      {Rec := MassOfStandart[SitArr[i].TOfPloho];
      HexCol := rgb(Rec.green, Rec.red, Rec.blue);
      Colorik.add(IntToStr( HexCol));


      //Memo1.Lines.Add(Colorik[currN]);
      inc(currN);
      Image1.Canvas.Brush.Color := HexCol;
      Image1.Canvas.Rectangle(0+CurrN*10,200,CurrN*10 + 10,400); }
    end
    else if flag then
    begin
      for j := 1 to High(SitNumArr) do
      begin
        Rec := MassOfStandart[j];
        HexCol := rgb(Rec.green, Rec.red, Rec.blue);
        coef:= Trunc( 100 - ( SitNumArr[j] / Max ) * 100 );
        if coef <> 100 then
        begin
          HexCol := LighterColor(HexCol, coef);
          Colorik.add(IntToStr( HexCol));
          inc(currN);
          Image1.Canvas.Brush.Color := HexCol;
          Image1.Canvas.Rectangle(0+CurrN*10,200,CurrN*10 + 10,400);
          Memo.Lines.Add(IntToStr(j) + ' ' + IntToStr(coef) + ' ' + IntToStr(HexCol));
        end;
      end;
      break;
    end;
  end;
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
  png: TPngImage;
begin
  // SPLASH SCREEN4iK
  png:= TPngImage(introIMG.Picture);
  Splash := TSplash.Create(png);
  //Splash.Show(true);

  N:=19;  // CHANGE PLS!!!!!!!!!!!

  XLSFile := GetCurrentDir + '\kek.xlsx'; // Положение excel-файла

  Xls_Open(XLSFile, Memo1);

  SetLength(MassOfStandart,N-1);
  shift := kek div n;

  creatingBasicColors(MassOfStandart, N, shift);

  QuickSort( length(SitArr)-1, SitArr);

  Colorik := TStringList.Create;

  maxVal := GetMaxVal(SitArr, N); // Максимальное значение происшествий в городе
  // ShowMessage( IntToStr(MaxVal) );

  FillMap(SitArr, Colorik, image1, MaxVal, Memo1);


  for i := 1 to n do
  begin
    // Отрисовка основных цветов
    Image1.Canvas.Brush.Color := RGB( MassOfStandart[i].red, MassOfStandart[i].green, MassOfStandart[i].blue );
    Image1.Canvas.Rectangle(0+i*20,0,i*20 + 20,200);
 end;


  sum := sum div N;
  Image1.Canvas.Brush.Color := MixColors(Colorik);
  Image1.Canvas.Rectangle(20,600,200,800);
  //Splash.Close;
end;

end.

