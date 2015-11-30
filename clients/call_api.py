#!/usr/bin/env python

import sys, os, time
import urllib
import urllib2
import json
import hashlib
import netifaces
import socket
import config

class RaspistreamApi:

	def __init__(self):
		self.baseurl = config.ApiBaseurl
		self.privateKey = config.PrivateKey
		self.doPrintLog = True
		self.password = config.ApiPassword

	def addLog(self, type, message, raspiSerial):
		"""Adds a log for the current Raspberry at
		the master server.
		"""
		url = self.baseurl + '/logs/add.json'

		timestamp = int(time.time())
		newHash = self.generateHMAC(timestamp, type, message)

		values = {
			'type' : type,
			'message' : message,
			#'raspberry_id' : raspiID
			'serial' : raspiSerial,
			#'hash' : newHash,
			#'timestamp' : str(timestamp)
		}
		json = self.getUrlContent(url, values)
		result = self.decodeJson(json)
		return result

	def changeIP(self, raspiSerial, ip):
		"""Change the IP of a raspberry identified by an ID
		"""
		url = self.baseurl + '/raspberries/changeIP.json'
		values = {
			'ip' : ip,
			'serial' : raspiSerial,
			'hostname' : self.getHostname(),
		}
		json = self.getUrlContent(url, values)
		result = self.decodeJson(json)
		return result

	def listLogs(self):
		url = self.baseurl + '/logs/index.json'
		json = self.getUrlContent(url)
		result = self.decodeJson(json)
		return result

	def listRaspberries(self):
		url = self.baseurl + '/raspberries/index.json'
		json = self.getUrlContent(url)
		result = self.decodeJson(json)
		return result

	def getUrlContent(self, url, values = None):
		try:
			if isinstance(values, dict):
				values['password'] = self.password
			else:
				values = {'password' : self.password}
			data = urllib.urlencode(values)
			req = urllib2.Request(url, data)
			response = urllib2.urlopen(req)
			content = response.read()
			#self.log(content)
			return content
		except urllib2.HTTPError as e:
			self.log(e)
			return False
		except urllib2.URLError as e:
			self.log(e)
			return False

	def decodeJson(self, jsonString):
		try:
			jsonObject = json.loads(jsonString)
			return jsonObject
		except ValueError as e:
			self.log(e)
			return False
		except TypeError as e2:
			self.log(e2)
			return False

	def generateHMAC(self, timestamp = None, *params):
		data = ''
		for param in params:
			data = data + str(param)
		if timestamp is None:
			timestamp = int(time.time())
		if timestamp is not False:
			data = data + str(timestamp)
		data = data + self.privateKey
		hash_object = hashlib.sha256(data)
		newHash = hash_object.hexdigest()
		return newHash

	def getserial(self):
		# Extract serial from cpuinfo file
		cpuserial = "0000000000000000"
		try:
			f = open('/proc/cpuinfo','r')
			for line in f:
				if line[0:6]=='Serial':
					cpuserial = line[10:26]
			f.close()
		except:
			cpuserial = "ERROR000000000"
		return cpuserial

	def getHostname(self):
		return socket.gethostname()

	def getIP(self):
		"""Get the current IP by pasing the interfaces"""
		#interfaces to check, in this order
		interfaces = ['eth0', 'wlan0']
		address = '0.0.0.0'
		for interface in interfaces:
			try:
				address = netifaces.ifaddresses(interface)[netifaces.AF_INET][0]['addr']
				break
			except:
				pass
		return address

	def log(self, message):
		if self.doPrintLog:
			print message

def main():
	#print sys.argv
	caller = RaspistreamApi()
	print caller.getHostname()

if __name__ == "__main__":
	main()
