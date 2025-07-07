@echo off
echo Setting up patient cleanup system...

REM Add registry entry for startup
regedit /s "%~dp0setup_startup.reg"

REM Create scheduled task
schtasks /create /tn "HospitalPatientCleanup" /tr "%~dp0run_cleanup.bat" /sc onlogon /rl highest

echo Setup complete! The cleanup system will now run at login.
pause