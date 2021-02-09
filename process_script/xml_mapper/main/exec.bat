 for %%f in (C:/inetpub/wwwroot/process_script/xml_mapper/batch_file/*.bat) do (
    SCHTASKS /CREATE /SC MINUTE /mo 1 /TN "\%%~nxf" /TR "C:\inetpub\wwwroot\process_script\xml_mapper\batch_file\%%~nxf" /f /ru A2B-VM2\A2B_Admin /rp S@jn8cQ9dX7W 
	)


