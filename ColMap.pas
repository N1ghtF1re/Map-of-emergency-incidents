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
  TColorArr = array[1..19] of TColor;
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


procedure FillMap(var head: PCityList; Colorik: TColorArr; {const MaxVal: integer}MaxArr:TSituationArr; Memo: TMemo; Canvas:TCanvas);

var
  i,j,currN:integer;
  flag: boolean;
  Rec: TRecordCust;
  HexCol : TColor;
  SitNumArr: array of integer;
  coef:integer;
  tmp: PCityList;
  k: integer;
  CurrNumOfSit:Integer;
  currY: Integer;
  CurrX: Integer;
begin
  flag := false;
  currN := 0;
  setlength(SitNumArr, N+1);
  for i := Low(SitNumArr) to High(SitNumArr) do
    SitNumArr[i] := 0;

  tmp := head^.adr;
  k:=1;
  curry:= 40;
  while tmp <> nil do
  begin
      currN := 1;
      for i:= 1 to N do
      begin
          Rec := MassOfStandart[i];
          HexCol := rgb(Rec.green, Rec.red, Rec.blue);
          CurrNumOfSit := tmp^.info.Sit[i];
          if maxarr[i] <> 0 then
            coef:= Trunc( 100 - ( CurrNumOfSit / MaxArr[i] {MaxVal} ) * 100 )
          else
            coef:= 100;
          if coef <> 100 then
          begin
              HexCol := LighterColor(HexCol, coef);
              Colorik[CurrN] := hexCol;
              inc(currN);
              //Memo.Lines.Add(IntToStr(i) + ' ' + IntToStr(coef) + ' ' + IntToStr(HexCol));
          end;
      end;
      tmp^.Info.ResultColor := MixColors(Colorik, currN-1);
      Memo.Visible := false;

      if k div 7 >= 1 then
      begin
        Curry:= curry + 200;
        k := k div 7;
      end;


      Canvas.Brush.Color := {GrayColor( }tmp^.Info.ResultColor{ )};
      Canvas.Rectangle(0+k*180,curry, k*180 + 180,CurrY + 200);
      canvas.TextOut(k*180+10,curry+50, tmp^.Info.Name);
      inc(k);
      tmp:= tmp^.adr;
  end;
end;

function maxWithArr(arr:TSituationArr):integer;
var i: integer;
  max: integer;
begin
  max:= arr[1];
  for I := Low(arr) to High(arr) do
  begin
    if arr[i] > max then
      max := arr[i];
  end;
  result := max;
end;

procedure TForm1.FormCreate(Sender: TObject);

var
  i:integer;
  XLSFile: string;
  png: TPngImage;
  Colorik:TColorArr;
  maxVal: integer;
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


  //maxVal := GetMaxVal(SitArr, N); // Максимальное значение происшествий в городе

  GetMaxVal(CityHead, MaxArr, N);
  //MaxVal := maxWithArr(MaxArr);
  FillMap(CityHead, Colorik, {MaxVal}MaxArr, Memo1, Image1.Canvas);


  for i := 1 to n do
  begin
    // Отрисовка основных цветов
    Image1.Canvas.Brush.Color := RGB( MassOfStandart[i].red, MassOfStandart[i].green, MassOfStandart[i].blue );
    Image1.Canvas.Rectangle(0+i*20,0,i*20 + 20,20);
 end;

  //Image1.Canvas.Brush.Color := MixColors(Colorik);
  //Image1.Canvas.Rectangle(20,600,200,800);
  //Splash.Close;
end;

end.

