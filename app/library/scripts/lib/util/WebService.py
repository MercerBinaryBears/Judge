import urllib
import urllib2
import json

class WebService:

	def __init__(self, baseUrl='http://localhost', baseParams={}):
		# normalize the url
		if not baseUrl.endswith('/'):
			baseUrl += '/'

		# save the
		self.baseUrl = baseUrl
		self.params = baseParams

	def _prepareUrl(self, url, params):
		params = dict(self.params.items() + params.items())
		url = self.baseUrl + url
		return url + '?' + urllib.urlencode(params)

	def _prepareResult(self, result='{}'):
		return json.loads(result)

	def get(self, url='', params={}, raw=False):
		url = self._prepareUrl(url, params)
		s = ''
		try:
			stream =  urllib2.urlopen(url)
			s = stream.read()
		except urllib2.HTTPError as httpError:
			s = httpError.read()

		# parse as json if not a raw request
		if not raw:
			s = self._prepareResult(s)
		return s

	def post(self, url='', params={}, raw=False):
		url = self.baseUrl + url
		params = dict(self.params.items() + params.items())
		s = ''
		try:
			stream =  urllib2.urlopen(url, urllib.urlencode(params))
			s = stream.read()
		except urllib2.HTTPError as httpError:
			s = httpError.read()

		# parse as json if not a raw request
		if not raw:
			s = self._prepareResult(s)
		return s