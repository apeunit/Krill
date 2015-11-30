#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys, os
import gi
from gi.repository import GObject
from gi.repository import Gst

class MonsterpipelineMain:

	def __init__(self):
		Gst.init(None)

		mainloop = GObject.MainLoop()
		self.initParse()
		mainloop.run()

	def initParse(self):
		defaults = {
			#basic command template for each sender, can be overridden
			"commandTemplate" : "rtpbin name=%(RtpbinName)s latency=%(latency)s flvmux name=%(MuxName)s streamable=true udpsrc caps=\"application/x-rtp, media=(string)video, clock-rate=(int)90000, encoding-name=(string)H264, sprop-parameter-sets=(string)\\\"J0KAKJWgKAv+WAeJE1A\\\=\\\,KM4CXIA\\\=\\\", ssrc=(uint)4240839776, payload=(int)96, timestamp-offset=(uint)283110971, seqnum-offset=(uint)3006\" port=%(SendRTPPort)s ! %(RtpbinName)s.recv_rtp_sink_0 %(RtpbinName)s. ! rtph264depay ! h264parse config-interval=1 ! avdec_h264 ! videorate ! videoconvert ! video/x-raw,framerate=%(framerate)s ! x264enc bitrate=800 byte-stream=false sliced-threads=true tune=zerolatency ! video/x-h264,profile=baseline,stream-format=byte-stream ! h264parse ! %(MuxName)s. udpsrc port=%(SendRTCPPort)s ! %(RtpbinName)s.recv_rtcp_sink_0 %(RtpbinName)s.send_rtcp_src_0 ! udpsink %(AudioHost)s port=%(ReceiveRTCPPort)s sync=false async=false %(TeeName)s. ! queue ! %(MuxName)s. %(MuxName)s. ! queue ! rtmpsink location=\"%(RtmpLocation)s\" sync=true async=false",
			"latency" : "10000",
			"framerate" : "25095/1000",
			"AudioPort1" : "5002", #Port Empfaengt Audio-Daten, von Audioempfaenger aus gesehen
			"AudioPort2" : "5003", #Port Empfaengt RTCP Daten, von Audioempfänger aus gesehen
			"AudioPort3" : "5007", #Port Sendet RTCP Daten, von Audioempfaenger aus gesehen
			"AudioHost" : "192.168.0.255",
			"TeeName" : "t",
		}
		
		configs = [
			{
				"RaspiName" : "barteldan",
				#only first one does the audio stuff, so this one has its own template
				"commandTemplate" : "rtpbin name=%(RtpbinName)s latency=%(latency)s flvmux name=%(MuxName)s streamable=true udpsrc caps=\"application/x-rtp, media=(string)video, clock-rate=(int)90000, encoding-name=(string)H264, sprop-parameter-sets=(string)\\\"J0KAKJWgKAv+WAeJE1A\\\=\\\,KM4CXIA\\\=\\\", ssrc=(uint)4240839776, payload=(int)96, timestamp-offset=(uint)283110971, seqnum-offset=(uint)3006\" port=%(SendRTPPort)s ! %(RtpbinName)s.recv_rtp_sink_0 %(RtpbinName)s. ! rtph264depay ! h264parse config-interval=1 ! avdec_h264 ! videorate ! videoconvert ! video/x-raw,framerate=%(framerate)s ! x264enc bitrate=800  byte-stream=false sliced-threads=true tune=zerolatency ! video/x-h264,profile=baseline,stream-format=byte-stream ! h264parse ! %(MuxName)s. udpsrc port=%(SendRTCPPort)s ! %(RtpbinName)s.recv_rtcp_sink_0 %(RtpbinName)s.send_rtcp_src_0 ! udpsink %(AudioHost)s port=%(ReceiveRTCPPort)s sync=false async=false udpsrc port=%(AudioPort1)s caps=\"application/x-rtp, media=(string)audio, clock-rate=(int)44100, encoding-name=(string)L16, encoding-params=(string)1, channels=(int)1\" ! %(RtpbinName)s.recv_rtp_sink_1 %(RtpbinName)s. ! queue ! rtpL16depay ! audioconvert ! lamemp3enc bitrate=128 ! audio/mpeg,mpegversion=1,layer=3,rate=44100,channels=1 ! mpegaudioparse ! tee name=%(TeeName)s ! queue ! %(MuxName)s. udpsrc port=%(AudioPort2)s ! %(RtpbinName)s.recv_rtcp_sink_1 %(RtpbinName)s.send_rtcp_src_1 ! udpsink host=%(AudioHost)s port=%(AudioPort3)s sync=false async=false %(MuxName)s. ! queue ! rtmpsink location=\"%(RtmpLocation)s\" sync=true async=false",
				"RtpbinName" : "rtpbin",
				"MuxName" : "mux",
				"SendRTPPort" : "5000",
				"SendRTCPPort" : "5001",
				"ReceiveRTCPPort" : "5005",
				"RtmpLocation" : "rtmp://streamone.apeunit.com:1935/liverepeater?doPublish=affenkaefig/mp4:testlive1",
			},
			{
				"RaspiName" : "bethselamin",
				"RtpbinName" : "rtpbin2",
				"MuxName" : "mux2",
				"SendRTPPort" : "6000",
				"SendRTCPPort" : "6001",
				"ReceiveRTCPPort" : "6005",
				"RtmpLocation" : "rtmp://streamone.apeunit.com:1935/liverepeater?doPublish=affenkaefig/mp4:testlive2",
			},
# 			{
# 				"RaspiName" : "brequinda",
# 				"RtpbinName" : "rtpbin3",
# 				"MuxName" : "mux3",
# 				"SendRTPPort" : "7000",
# 				"SendRTCPPort" : "7001",
# 				"ReceiveRTCPPort" : "7005",
# 				"RtmpLocation" : "rtmp://streamone.apeunit.com:1935/liverepeater?doPublish=affenkaefig/mp4:testlive3",
# 			},
			{
				"RaspiName" : "bartrax",
				"RtpbinName" : "rtpbin4",
				"MuxName" : "mux4",
				"SendRTPPort" : "8000",
				"SendRTCPPort" : "8001",
				"ReceiveRTCPPort" : "8005",
				"RtmpLocation" : "rtmp://streamone.apeunit.com:1935/liverepeater?doPublish=affenkaefig/mp4:testlive4",
			},
		]
		
		command = ''
		for config in configs:
			settings = dict(defaults.items() + config.items())
			commandTemplate = settings["commandTemplate"]
			partCommand = commandTemplate % settings
			command = command + ' ' + partCommand
		print command
		
		self.audioSenderPipeline = Gst.parse_launch(command)
		self.audioSenderPipeline.set_state(Gst.State.PLAYING)
		##recursively print elements
		#self.listElements(self.audioSenderPipeline)
		

	def listElements(self, bin, level = 0):
		try:
			iterator = bin.iterate_elements()
			#print iterator
			while True:
				elem = iterator.next()
				if elem[1] is None:
					break
				print level * '** ' + str(elem[1])
				## uncomment to print pads of element
				#self.iteratePads(elem[1])
				## call recursively
				self.listElements(elem[1], level + 1)
		except AttributeError:
			pass

	def iteratePads(self, element):
		try:
			iterator = element.iterate_pads()
			while True:
				pad = iterator.next()
				if pad[1] is None:
					break
				print 'pad: ' + str(pad[1])
		except AttributeError:
			pass

	def on_message(self, bus, message):
		t = message.type
		#if t == Gst.MESSAGE_EOS:
		#	print "MESSAGE_EOS"
		#elif t == Gst.MESSAGE_ERROR:
		#	err, debug = message.parse_error()
		#	print "Error: %s" % err, debug
		#else:
		pass
		#print "ANOTHA MESSEGE " + str(t)
		
def main():
	pipeline = MonsterpipelineMain()

if __name__ == "__main__":
	main()
