![alt text][logo]
# Krill
Krill is a wireless, multi-camera livestreaming solution. **Warning:** This repo does not aim to be a ready to install Application.

## License
Krill is licensed under The MIT License.
For full copyright and license information, please see the LICENSE.txt.

## Background
We developed this project to livestream a series of talks by the "Akademie der Künste Berlin". Multiple wireless camera modules, built with Rasberry Pis and a battery pack, stream their videos to a master server via wifi. There these streams get combined into a single image and streamed to the web.

## Components
![alt text][detail]

### Master/Webinterface
This was a PC connected to the (highspeed) internet of the venue. It created it's own wireless network for the clients to connect to. It's job was to manage the different Clients, combine their streams with the local audio stream (coming from an audio input on the master) and send the stream to the web. Hosted on it was a webinterface to control the clients and the stream.

The Webinterface code can be found in the "webinterface" folder.

* Webinterface
  * Written in cakePHP
  * Needs a MySQL Database
    * to store Information about the connected Clients
    * to store Configuration options for the single Clients
    * to Store Logs from the single Clients
  *  Offers API Endpoints for the Clients to connect to the Master
  * uses phpseclib to connect to the Clients via SSH, issue commands and control them

### Clients/Cameras
These camera modules consist of a Raspberry Pi with a wifi module, a camera module and a battery pack in a custom 3D printed case. After booting the clients automatically connect to the wifi provided by the master and run a python script which registers with the client.

Python client code can be found in the clients folder.

* Python Client Code:
  * Offers a Library to communicate with the master server
  * Has a few scripts which for example should be run after boot to register at the master

## Installing

There is no simple howto on installing this setup since it's tailored for a pretty specific use. We wanted to open source this project to maybe save other developers working on similar projects some hassle.


[logo]: http://www.apeunit.com/wp-content/uploads/2015/04/Krill_003.png "Krill Image"

[detail]: http://www.apeunit.com/wp-content/uploads/2015/04/Krill_02_2.png "Krill Details"
