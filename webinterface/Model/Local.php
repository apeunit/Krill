<?php
App::uses('AppModel', 'Model');
/**
 * Local Model
 * To Start and Stop the Local Stream which combines the Streams of the Clients
 * Basically runs gstreamer commands on the Local machine
 *
 * @property Local $Local
 */
class Local extends AppModel {
	public $useTable = false; // This model does not use a database table

	public function startLocalStream() {
		$cmd = 'gst-launch-1.0 -v rtpbin name=rtpbin latency=2000 videomixer name=mix sink_1::xpos=640 sink_2::ypos=360 sink_3::xpos=640 sink_3::ypos=360  flvmux name=mux ! queue ! rtmpsink location="rtmp://streamone.apeunit.com:1935/liverepeater?doPublish=affenkaefig/mp4:testlive2" flvmux name=mux2 ! queue ! rtmpsink location="rtmp://streamone.apeunit.com:1935/liverepeater?doPublish=affenkaefig/mp4:testlive3" udpsrc caps="application/x-rtp, media=(string)video, clock-rate=(int)90000, encoding-name=(string)H264,sprop-parameter-sets=(string)\"J2QAKKwrQFAX/LAPEiagAA\\=\\=\\,KO4CXLAA\", ssrc=(uint)1443704322, payload=(int)96, timestamp-offset=(uint)4133139946, seqnum-offset=(uint)52698" port=5000 ! rtpbin.recv_rtp_sink_0 rtpbin.recv_rtp_src_0_* ! rtph264depay ! h264parse config-interval=1 ! avdec_h264 ! videorate ! videoconvert ! "video/x-raw,framerate=25/1" ! tee name=v ! queue ! mix.sink_0 udpsrc port=5001 ! rtpbin.recv_rtcp_sink_0 rtpbin.send_rtcp_src_0 ! udpsink 192.168.12.255 port=5005 sync=false async=false udpsrc caps="application/x-rtp, media=(string)video, clock-rate=(int)90000, encoding-name=(string)H264,sprop-parameter-sets=(string)\"J2QAKKwrQFAX/LAPEiagAA\\=\\=\\,KO4CXLAA\", ssrc=(uint)1443704322, payload=(int)96, timestamp-offset=(uint)4133139946, seqnum-offset=(uint)52698" port=6000 ! rtpbin.recv_rtp_sink_2 rtpbin.recv_rtp_src_2_* ! rtph264depay ! h264parse config-interval=1 ! avdec_h264 ! videorate ! videoconvert ! "video/x-raw,framerate=25/1" ! queue ! mix.sink_1 udpsrc port=6001 ! rtpbin.recv_rtcp_sink_2 rtpbin.send_rtcp_src_2 ! udpsink 192.168.12.255 port=6005 sync=false async=false udpsrc caps="application/x-rtp, media=(string)video, clock-rate=(int)90000, encoding-name=(string)H264,sprop-parameter-sets=(string)\"J2QAKKwrQFAX/LAPEiagAA\\=\\=\\,KO4CXLAA\", ssrc=(uint)1443704322, payload=(int)96, timestamp-offset=(uint)4133139946, seqnum-offset=(uint)52698" port=7000 ! rtpbin.recv_rtp_sink_3 rtpbin.recv_rtp_src_3_* ! rtph264depay ! h264parse config-interval=1 ! avdec_h264 ! videorate ! videoconvert ! "video/x-raw,framerate=25/1" ! queue ! mix.sink_2 udpsrc port=7001 ! rtpbin.recv_rtcp_sink_3 rtpbin.send_rtcp_src_3 ! udpsink 192.168.12.255 port=7005 sync=false async=false udpsrc caps="application/x-rtp, media=(string)video, clock-rate=(int)90000, encoding-name=(string)H264,sprop-parameter-sets=(string)\"J2QAKKwrQFAX/LAPEiagAA\\=\\=\\,KO4CXLAA\", ssrc=(uint)1443704322, payload=(int)96, timestamp-offset=(uint)4133139946, seqnum-offset=(uint)52698" port=8000 ! rtpbin.recv_rtp_sink_4 rtpbin.recv_rtp_src_4_* ! rtph264depay ! h264parse config-interval=1 ! avdec_h264 ! videorate ! videoconvert ! "video/x-raw,framerate=25/1" ! queue ! mix.sink_3 udpsrc port=8001 ! rtpbin.recv_rtcp_sink_4 rtpbin.send_rtcp_src_4 ! udpsink 192.168.12.255 port=8005 sync=false async=false udpsrc port=5002 caps="application/x-rtp, media=(string)audio, clock-rate=(int)44100, encoding-name=(string)L16, encoding-params=(string)1, channels=(int)1" ! rtpbin.recv_rtp_sink_1 rtpbin.recv_rtp_src_1_* ! queue ! rtpL16depay ! audioconvert ! lamemp3enc bitrate=128 ! audio/mpeg,mpegversion=1,layer=3,rate=44100,channels=1 ! mpegaudioparse ! queue ! tee name=a ! queue ! mux. a. ! queue ! mux2. mix. ! queue ! videoconvert ! x264enc bitrate=2300  byte-stream=false sliced-threads=true tune=zerolatency ! video/x-h264,profile=baseline,stream-format=byte-stream ! h264parse ! queue ! mux. v. ! queue ! videoconvert ! x264enc bitrate=650  byte-stream=false sliced-threads=true tune=zerolatency ! video/x-h264,profile=baseline,stream-format=byte-stream ! h264parse ! queue ! mux2. udpsrc port=5003 ! rtpbin.recv_rtcp_sink_1 rtpbin.send_rtcp_src_1 ! udpsink host=192.168.12.255 port=5007 sync=false async=false';
		$cmd .= ' > /dev/null';
		$cmd .= ' 2>/dev/null';
		$cmd .= ' &';
// 		debug($cmd); die;
		exec($cmd);
		return true;
	}

	public function stopLocalStream() {
		$ps = exec("ps aux | grep x264enc | awk '{print $2}' | xargs kill");
		return true;
	}

	public function startLocalAudio() {
		//Start sending of audio via gstreamer
		$cmd = "gst-launch-1.0 -v rtpbin name=rtpbin alsasrc device=plughw:Device ! audioconvert ! audio/x-raw,channels=1,depth=16,width=16,rate=44100 ! rtpL16pay  ! rtpbin.send_rtp_sink_1 rtpbin.send_rtp_src_1 ! udpsink host=192.168.12.255 port=5002 rtpbin.send_rtcp_src_1 ! udpsink host=192.168.168.12.255 port=5003 sync=false async=false udpsrc port=5007 ! rtpbin.recv_rtcp_sink_1";
		$cmd .= ' > /dev/null';
		$cmd .= ' 2>/dev/null';
		$cmd .= ' &';
// 		debug($cmd); die;
		exec($cmd);
		return true;
	}

	public function stopLocalAudio() {
		$ps = exec("ps aux | grep rtpL16pay | awk '{print $2}' | xargs kill");
		return true;
	}

	public function isStreamingAudio() {
		$ps = exec('ps faux | grep rtpL16pay');
// 		debug($ps); die;
		if (strpos($ps,'gst-launch') !== false) {
			return true;
		}
		return false;
	}

	public function isStreaming() {
		$ps = exec('ps faux | grep x264enc');
// 		debug($ps); die;
		if (strpos($ps,'gst-launch') !== false) {
			return true;
		}
		return false;
	}
}
