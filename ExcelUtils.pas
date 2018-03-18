unit ExcelUtils;

interface
uses  CityListUtils;

type
TSituationRec = Record
    City: string[40];
    TOfPloho: integer;
  end;
  TSitArr = array of TSituationRec;


procedure Xls_Open(XLSFile:string; var head: PCityList);
implementation

uses
 System.SysUtils, System.Variants, System.Classes,
 ComObj,vcl.dialogs;

procedure Xls_Open(XLSFile:string; var head: PCityList);
 const
  xlCellTypeLastCell = $0000000B;
var
  ExlApp, Sheet: OLEVariant;
  j, c, r:integer;
  CI, SI: integer;
  tmp: string;
  currName: string;
  lastadr: PCityList;
  SitNum:integer;
  city:string;
begin
  ExlApp := CreateOleObject('Excel.Application');

  ExlApp.Visible := false;

  ExlApp.Workbooks.Open(XLSFile);

  Sheet := ExlApp.Workbooks[ExtractFileName(XLSFile)].WorkSheets[1];

  Sheet.Cells.SpecialCells(xlCellTypeLastCell, EmptyParam).Activate;



  r := ExlApp.ActiveCell.Row;
  c := ExlApp.ActiveCell.Column;
  //SetLength(SitArr, r-2);

  // Excel SORT by City name
  sheet.Range[Sheet.Cells[2,1],Sheet.Cells[r,c]].Sort
  (Key1:=sheet.Range[Sheet.Cells[2,1],Sheet.Cells[r,1]], Order1:=1, Header:=0, OrderCustom:=1, MatchCase:=False, Orientation:=1, DataOption1:=0);

  SI:= 57+26; // Sutiation Column
  CI:=1;      // City name Column
  currName := sheet.cells[2, 1];
  lastadr:= insertCityList(head, currName);
  j:= 2;
  while j <= r do
  begin
    city := sheet.cells[j, CI];
    while currname = city do
    begin
      tmp := sheet.cells[j, SI];
      if tmp <> '' then
      begin
        if tmp[2] in ['0'..'9'] then
          SitNum := StrToInt( tmp[1] + tmp[2] )
        else
          SitNum := StrToInt(tmp[1]);
        inc(lastadr^.Info.Sit[SitNum]);
      end

      city := sheet.cells[j, CI];
      inc(j);
    end;

    if j <= r then
    begin
      currname := sheet.cells[j, CI];
      lastadr:= insertCityList(head, currName);
    end;
    {SitArr[j-2].City := sheet.cells[j, CI];
    tmp := sheet.cells[j, SI];

    if tmp[2] in ['0'..'9'] then
      SitArr[j-2].TOfPloho := StrToInt( tmp[1] + tmp[2] )
    else
      SitArr[j-2].TOfPloho := StrToInt(tmp[1]);}
  end;
  tmp := #0;

 ExlApp.DisplayAlerts := False; // <-
 ExlApp.Quit;

 ExlApp := Unassigned;
 Sheet := Unassigned;

end;
end.
