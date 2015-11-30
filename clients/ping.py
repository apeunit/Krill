from call_api import RaspistreamApi

caller = RaspistreamApi()
caller.addLog(2, 'hourly cronjob', caller.getserial())
caller.changeIP(caller.getserial(), caller.getIP())
