unit CreateBasicColors;

interface
type
  TColorQueue = (green, red, blue);
  TRecordCust = record
    green,red,blue:Integer;
  end;

procedure creatingBasicColors(var MassOfStandart: array of TRecordCust; var N:integer; shift: Integer);

implementation

procedure Succa(var Clr: TColorQueue);
begin
clr := succ(clr);
if( ord(Clr) > 2 ) then
Clr := green;
end;

procedure SuccBool(var boolTemp:Boolean);
begin
if boolTemp then
  boolTemp := False
else
  boolTemp := True;
end;

function LessThen255(ColorT: TColorQueue; var K: TRecordCust): boolean;
begin
case ColorT of
blue: Result:= K.blue < 255;
green: Result:= K.green < 255;
red: Result:= K.red < 255
end;
end;

function MoreThen0(ColorT: TColorQueue; K: TRecordCust): boolean;
begin
case ColorT of
blue: Result:= K.blue > 0;
green: Result:= K.green > 0;
red: Result:= K.red > 0
end;
end;

procedure IncKColorT(ColorT: TColorQueue; var K: TRecordCust);
begin
case ColorT of
blue: Inc(K.blue);
green: Inc(K.green);
red: Inc(K.red);
end;
end;

procedure DecKColorT(ColorT: TColorQueue; var K: TRecordCust);
begin
case ColorT of
blue: Dec(K.blue);
green: Dec(K.green);
red: Dec(K.red);
end;
end;

procedure creatingBasicColors(var MassOfStandart: array of TRecordCust; var N:integer; shift: Integer);
var
  trg_plus, exitbool: Boolean;
  i:Byte;
  deltaShift: Integer;
  colorT: TColorQueue;
  K: TRecordCust;
begin
K.red := 255;
K.green := 0;
K.blue := 0;

MassOfStandart[1].green := K.green;
MassOfStandart[1].red := K.red;
MassOfStandart[1].blue := K.blue;

trg_plus := true;
colorT := green;

for i:=2 to N do
begin
	exitbool := false;
	deltaShift := shift;
	while not(exitbool) do
	begin
		case trg_plus of
		true:
			begin
			while LessThen255(colorT, K) and (deltaShift <> 0) do
				begin
				IncKColorT(colorT, K);
				dec(deltaShift);
				end;
			end
		else
			begin
			while MoreThen0(colorT, K) and (deltaShift <> 0) do
				begin
				DecKColorT(colorT, K);
				dec(deltaShift);
				end;
			end;
    end;

		if (deltaShift = 0) then
			begin
			MassOfStandart[i].green := K.green;
			MassOfStandart[i].red := K.red;
			MassOfStandart[i].blue := K.blue;
			exitbool:=true;
			end
		else
			begin
			Succbool(trg_plus);
			SuccA(colorT);
			end;
	end;
end;
end;

end.
