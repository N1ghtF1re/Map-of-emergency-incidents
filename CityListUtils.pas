unit CityListUtils;

interface

uses
  vcl.graphics,Vcl.Dialogs,System.SysUtils;

type
  TSituationArr = array[1..19] of word;
  TCityInfo = record
    Name: string[40];
    Sit: TSituationArr;
    ResultColor: TColor;
  end;
  PCityList = ^CityList;
  CityList = record
    Info: TCityInfo;
    adr: PCityList;
  end;
procedure savePriceList(const Head:PCityList; Filename:string);
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
 procedure savePriceList(const Head:PCityList; Filename:string);
   var
   Temp:PCityList;
   f: file of TCityInfo;
   begin
   AssignFile(f,Filename);
   Rewrite(f);
   Seek(f,Filesize(f));
   if Head.ADR<>nil then
     begin
     temp:=Head.ADR;
     while Temp<> nil do
       begin
         write(f,Temp.Info);
         Temp:=Temp^.ADR;
       end;
     end;
   Close(f);
   end;
      procedure readPricelist(const Head:PCityList; Filename:string);
   var
   Temp:PCityList;
   Info:TCityInfo;
   TInfo:TCityInfo;
   f: file of TCityInfo;
   begin

   if fileExists(Filename) then
     begin
       AssignFile(f,Filename);
     Reset(f);
     Head^.ADR:=nil;
     temp:=Head;
     while not(Eof(f)) do
     begin
       read(f,TInfo);
       new(Temp^.adr);
       Temp:=Temp^.ADR;
       Temp.ADR:=nil;
       temp.Info:=TInfo;
     end;
     end
     else
     begin
       Rewrite(f);
       ShowMessage('No such file or directiory');
       readPricelist(Head,'kek.brakh');
     end;
     close(f)
   end;
end.
