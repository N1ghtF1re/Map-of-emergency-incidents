unit HSLUtils;

interface

uses
  Windows, Graphics,System.Classes;
  type
    TSituationRec = Record
    City: string[40];
    TOfPloho: integer;
  end;
  TSitArr = array of TSituationRec;

// Получить цвет, темнее исходного на Percent процентов
function DarkerColor(const Color : TColor; Percent : Integer) : TColor;
// Получить цвет, светлее исходного на Percent процентов
function LighterColor(const Color : TColor; Percent : Integer) : TColor;
// Смешать несколько цветов и получить средний
function MixColors(Colors: TStringList{array of TColor}): TColor;
// Сделать цвет черно-белым
function GrayColor(Color : TColor) : TColor;

implementation

uses
  System.SysUtils;

function DarkerColor(const Color: TColor; Percent: Integer): TColor;
var
  R, G, B: Byte;
begin
  Result := Color;
  if Percent <= 0 then
    Exit;
  if Percent > 100 then
    Percent := 100;
  Result := ColorToRGB(Color);
  R := GetRValue(Result);
  G := GetGValue(Result);
  B := GetBValue(Result);
  R := R - R * Percent div 100;
  G := G - G * Percent div 100;
  B := B - B * Percent div 100;
  Result := RGB(R, G, B);
end;

function LighterColor(const Color: TColor; Percent: Integer): TColor;
var
  R, G, B: Byte;
begin
  Result := Color;
  if Percent <= 0 then
    Exit;
  if Percent > 100 then
    Percent := 100;
  Result := ColorToRGB(Result);
  R := GetRValue(Result);
  G := GetGValue(Result);
  B := GetBValue(Result);
  R := R + (255 - R) * Percent div 100;
  G := G + (255 - G) * Percent div 100;
  B := B + (255 - B) * Percent div 100;
  Result := RGB(R, G, B);
end;

function MixColors(Colors: TStringList{array of TColor}): TColor;
var
  R, G, B: Integer;
  i: Integer;
  L: Integer;
begin
  R := 0;
  G := 0;
  B := 0;
  for i := 0 to Colors.Count-1 do
  begin
    Result := ColorToRGB(TColor(StrToInt(Colors[i])) );
    R := R + GetRValue(Result);
    G := G + GetGValue(Result);
    B := B + GetBValue(Result);
  end;
  L := Colors.Count;
  Result := RGB(R div L, G div L, B div L);
end;

function GrayColor(Color: TColor): TColor;
var
  Gray: Byte;
begin
  Result := ColorToRGB(Color);
  Gray := (GetRValue(Result) + GetGValue(Result) + GetBValue(Result)) div 3;
  Result := RGB(Gray, Gray, Gray);
end;
function max(a,b:Integer):Integer;
begin
  if A>b then
    result:=a
    else
    result:=b;
end;
function GetMaxVal(sitarr:TSitArr):Integer;
var
  tmpcity:string;
  A:array [1..19]  of Integer;
  i,prmax,currmax,j:Integer;
begin
  for i:= 0 to length(SitArr) - 1 do
  begin
    while
    sitarr[i].city = tmpcity do
    begin
    Inc(A[SitArr[i].TOfPloho]);
    end;
   currmax:=A[1];
   for j:=1 to 19 do
   begin
     if currmax<A[j] then
     Currmax:=A[j];
   end;
   tmpcity:=sitarr[i].city;
   result:=max(prmax,currmax);
  end;
end;

end.
