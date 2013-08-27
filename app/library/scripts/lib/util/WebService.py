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

	def get(self, url='', params={}):
		url = self._prepareUrl(url, params)
		s = ''
		try:
			stream =  urllib2.urlopen(url)
			s = stream.read()
		except urllib2.HTTPError as httpError:
			s = httpError.read()
		return self._prepareResult(s)

	def post(self, url='', params={}):
		url = self.baseUrl + url
		params = dict(self.params.items() + params.items())
		s = ''
		try:
			stream =  urllib2.urlopen(url, params)
			s = stream.read()
		except urllib2.HTTPError as httpError:
			s = httpError.read()

		return self._prepareResult(s)