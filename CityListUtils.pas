unit CityListUtils;

interface

type
  TSituationArr = array[1..19] of word;
  TCityInfo = record
    Name: string[40];
    Sit: TSituationArr;
  end;
  PCityList = ^CityList;
  CityList = record
    Info: TCityInfo;
    adr: PCityList;
  end;
procedure createCityList(var head: PCityList);
function insertCityList(head: PCityList; Name: string):PCityList;

implementation
const
  NullArr: TSituationArr = (0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

procedure createCityList(var head: PCityList);
begin
  new(head);
  head^.adr := nil;
end;

function insertCityList(head: PCityList; Name: string):PCityList;
var
  temp: PCityList;
begin
  temp := head;
  while temp.adr <> nil do
  begin
    temp:= temp^.adr;
  end;
  new(temp^.adr);
  temp:= temp^.adr;
  temp^.adr := nil;
  temp^.Info.Name := Name;
  temp^.Info.Sit := NullArr;
  Result:= temp;
end;

end.
