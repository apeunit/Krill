#!/usr/bin/env python

import sys, os
import gi
from gi.repository import GObject
from gi.repository import Gst

class AudioSenderMain:

	def __init__(self):
		Gst.init(None)

		mainloop = GObject.MainLoop()
		self.initParse()
		mainloop.run()

	def initParse(self):
		## escape all " with \"
		command = 'rtpbin name=rtpbin latency=10000 udpsrc caps=\"application/x-rtp, media=(string)video, clock-rate=(int)90000, encoding-name=(string)H264, sprop-parameter-sets=(string)\\\"J0KAKJWgKAv+WAeJE1A\\\=\\\,KM4CXIA\\\=\\\", ssrc=(uint)4240839776, payload=(int)96, timestamp-offset=(uint)283110971, seqnum-offset=(uint)3006\" port=5000 ! rtpbin.recv_rtp_sink_0 rtpbin. ! rtph264depay ! h264parse config-interval=1 ! avdec_h264 ! videorate ! videoconvert ! video/x-raw,framerate=29850/1000 ! autovideosink udpsrc port=5001 name=maxrockt ! rtpbin.recv_rtcp_sink_0 rtpbin.send_rtcp_src_0 ! udpsink 192.168.0.108 port=5005 sync=false async=false udpsrc port=5002 caps=\"application/x-rtp, media=(string)audio, clock-rate=(int)44100, encoding-name=(string)L16, encoding-params=(string)1, channels=(int)1\" ! rtpbin.recv_rtp_sink_1 rtpbin. ! queue ! rtpL16depay ! autoaudiosink udpsrc port=5003 ! rtpbin.recv_rtcp_sink_1 rtpbin.send_rtcp_src_1 ! udpsink host=192.168.0.78 port=5007 sync=false async=false'
		#print command
		self.audioSenderPipeline = Gst.parse_launch(command)
		self.audioSenderPipeline.set_state(Gst.State.PLAYING)
		bus = self.audioSenderPipeline.get_bus()
		print bus
		bus.add_signal_watch()
		bus.enable_sync_message_emission()
		bus.connect("message", self.on_message)

		rtpBin = self.audioSenderPipeline.get_by_name('rtpbin')
		print rtpBin

		print self.audioSenderPipeline.get_by_name('maxrockt')

		#recursively print elements
		self.listElements(self.audioSenderPipeline)
		

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

AudioSenderMain()