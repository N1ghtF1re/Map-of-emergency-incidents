unit ColMap;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms,pngimage, Vcl.Dialogs, Vcl.ExtCtrls,ExcelUtils,
  HSLUtils,SplashScreen, Vcl.StdCtrls, ComObj, CreateBasicColors, CityListUtils;
Const
//  n = 17;
  kek = trunc(255*5.78);
//  shift = kek div n;
type

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
  CityHead: PCityList;
  MaxArr: TSituationArr;
implementation

{$R *.dfm}

function max(a,b:Integer):Integer;
begin
  if A>b then
    result:=a
    else
    result:=b;
end;

procedure GetMaxVal(head: PCityList; var MaxArr: TSituationArr; const N: integer);
var
  i,j: integer;
  tmp: PCityList;
begin
  for i := 1 to n do
    MaxArr[i] := 0;

  tmp:= head^.adr;

  while tmp <> nil do
  begin
    for j := 1 to N do
    begin
      if tmp^.Info.Sit[j] > MaxArr[j] then
        MaxArr[j] := tmp^.Info.Sit[j];
    end;

    tmp:= tmp^.adr;
  end;



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
    {if SitArr[i].City = 'Минский район' then
    begin
      flag := true;

      inc(SitNumArr[Sitarr[i].TOfPloho]);

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
    end;   }
  end;
end;

procedure TForm1.FormCreate(Sender: TObject);
var
  i:integer;
  Colorik: TStringList;
  XLSFile: string;
  png: TPngImage;
begin
  // SPLASH SCREEN4iK
  png:= TPngImage(introIMG.Picture);
  Splash := TSplash.Create(png);
  //Splash.Show(true);

  CreateCityList(CityHead);

  N:=19;  // CHANGE PLS!!!!!!!!!!!

  XLSFile := GetCurrentDir + '\kek.xlsx'; // Положение excel-файла

  Xls_Open(XLSFile, CityHead);

  SetLength(MassOfStandart,N-1);
  shift := kek div n;

  creatingBasicColors(MassOfStandart, N, shift);

  // QuickSort( length(SitArr)-1, SitArr);

  Colorik := TStringList.Create;

  //maxVal := GetMaxVal(SitArr, N); // Максимальное значение происшествий в городе

  GetMaxVal(CityHead, MaxArr, N);

  FillMap(SitArr, Colorik, image1, MaxVal, Memo1);


  for i := 1 to n do
  begin
    // Отрисовка основных цветов
    Image1.Canvas.Brush.Color := RGB( MassOfStandart[i].red, MassOfStandart[i].green, MassOfStandart[i].blue );
    Image1.Canvas.Rectangle(0+i*20,0,i*20 + 20,200);
 end;

  //Image1.Canvas.Brush.Color := MixColors(Colorik);
  Image1.Canvas.Rectangle(20,600,200,800);
  //Splash.Close;
end;

end.

