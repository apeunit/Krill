#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import config

settings = {
    "raspividExecutable" : "/opt/vc/bin/raspivid",
    "gstExecutable" : "/usr/bin/gst-launch-1.0 -v",
    "latency" : "1000",
    "framerate" : "25000/1000",
    "SendRTPPort" : config.SendRTPPort,
    "SendRTCPPort" : config.SendRTCPPort,
    "ReceiveRTCPPort" : config.ReceiveRTCPPort,
    "Host": config.AudioHost,
}

commandTemplate = "%(gstExecutable)s rtpbin latency=%(latency)s name=rtpbin rpicamsrc bitrate=800000 video-stabilisation=true typefind=true exposure-mode=10 iso=800 awb-mode=6 ! video/x-h264, width=(int)640, height=(int)360, framerate=(fraction)%(framerate)s, key-framerate=25 ! h264parse ! video/x-h264,profile=baseline,level=3.1,framerate=%(framerate)s,stream-format=avc ! rtph264pay config-interval=1 pt=96 ! rtpbin.send_rtp_sink_0 rtpbin.send_rtp_src_0 ! udpsink host=%(Host)s port=%(SendRTPPort)s rtpbin.send_rtcp_src_0 ! udpsink host=%(Host)s port=%(SendRTCPPort)s sync=false async=false udpsrc port=%(ReceiveRTCPPort)s ! rtpbin.recv_rtcp_sink_0"
command = commandTemplate % settings
#print command
os.system(command)
