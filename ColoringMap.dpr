program ColoringMap;

uses
  Vcl.Forms,
  ColMap in 'ColMap.pas' {Form1},
  HSLUtils in 'HSLUtils.pas';

{$R *.res}

begin
  Application.Initialize;
  Application.MainFormOnTaskbar := True;
  Application.CreateForm(TForm1, Form1);
  Application.Run;
end.
