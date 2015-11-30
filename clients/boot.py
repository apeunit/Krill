from call_api import RaspistreamApi
import time

# this script should be run on boot of the raspberry

caller = RaspistreamApi()
tries = 0
# try 10 times to register with master
while tries < 10:
    # add a log to the master that the device has booted
    result = caller.addLog(1, 'boot cronjob', caller.getserial())
    print result
    # register with the master with the serial and the current IP
    result2 = caller.changeIP(caller.getserial(), caller.getIP())
    print result2
    if result == False:
        print str(tries) + ' error'
        tries = tries + 1
        time.sleep(10)
    else:
        break
