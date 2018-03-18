unit ExcelUtils;

interface
type
TSituationRec = Record
    City: string[40];
    TOfPloho: integer;
  end;
  TSitArr = array of TSituationRec;

procedure Xls_Open(XLSFile:string; var SitArr: TSitArr);
implementation

uses
 System.SysUtils, System.Variants, System.Classes,
 ComObj, CityListUtils;

procedure Xls_Open(XLSFile:string; var head: PCityList);
 const
  xlCellTypeLastCell = $0000000B;
var
  ExlApp, Sheet: OLEVariant;
  j, c, r:integer;
  CI, SI: integer;
  tmp: string;
  currName: string;
begin
  ExlApp := CreateOleObject('Excel.Application');

  ExlApp.Visible := false;

  ExlApp.Workbooks.Open(XLSFile);

  Sheet := ExlApp.Workbooks[ExtractFileName(XLSFile)].WorkSheets[1];

  Sheet.Cells.SpecialCells(xlCellTypeLastCell, EmptyParam).Activate;



  r := ExlApp.ActiveCell.Row;
  c := ExlApp.ActiveCell.Column;
  SetLength(SitArr, r-2);

  // Excel SORT by City name
  sheet.Range[Sheet.Cells[2,1],Sheet.Cells[r,c]].Sort
  (Key1:=sheet.Range[Sheet.Cells[2,1],Sheet.Cells[r,1]], Order1:=1, Header:=0, OrderCustom:=1, MatchCase:=False, Orientation:=1, DataOption1:=0);

  SI:= 57+26; // Sutiation
  CI:=1;      // City name
  currName:= string;
  for j:= 2 to r do
  begin

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
