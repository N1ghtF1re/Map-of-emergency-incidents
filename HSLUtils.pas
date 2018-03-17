// ------------------------------------------------------------------------------
//
// HSL - RGB colour model conversions
//
// These four functions can be used to convert between the RGB and HSL colour
// models.  RGB values are represented using the 0-255 Windows convention and
// always encapsulated in a TColor 32 bit value.  HSL values are available as
// either 0 to 1 floating point (double) values or as a 0 to a defined integer
// value.  The colour common dialog box uses 0 to 240 by example.
//
// The code is based on that found (in C) on:
//
// http:/www.r2m.com/win-developer-faq/graphics/8.html
//
// Grahame Marsh 12 October 1997
//
// Freeware - you get it for free, I take nothing, I make no promises!
//
// Please feel free to contact me: grahame.s.marsh@corp.courtaulds.co.uk
//
// Revison History:
// Version 1.00 - initial release  12-10-1997
//
// ------------------------------------------------------------------------------

unit HSLUtils;

interface

uses
  Windows, Graphics;

// Получить цвет, темнее исходного на Percent процентов
function DarkerColor(const Color : TColor; Percent : Integer) : TColor;
// Получить цвет, светлее исходного на Percent процентов
function LighterColor(const Color : TColor; Percent : Integer) : TColor;
// Смешать несколько цветов и получить средний
function MixColors(const Colors : array of TColor) : TColor;
// Сделать цвет черно-белым
function GrayColor(Color : TColor) : TColor;

implementation

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

function MixColors(const Colors: array of TColor): TColor;
var
  R, G, B: Integer;
  i: Integer;
  L: Integer;
begin
  R := 0;
  G := 0;
  B := 0;
  for i := Low(Colors) to High(Colors) do
  begin
    Result := ColorToRGB(Colors[i]);
    R := R + GetRValue(Result);
    G := G + GetGValue(Result);
    B := B + GetBValue(Result);
  end;
  L := Length(Colors);
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
end.
