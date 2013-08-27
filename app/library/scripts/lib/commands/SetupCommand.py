from lib.util.ConfigFile import ConfigFile
from lib.util.WebService import WebService
from lib.commands.Command import Command

class SetupCommand(Command):
	def execute(self, arguments):
		# read config values
		with ConfigFile() as config_file:
			# prompt the values
			base_url = raw_input('Base Url for contest api: ').strip()
			api_key = raw_input('Your API Key: ').strip()

			# set them
			config_file.set('base_url', base_url)
			config_file.set('api_key', api_key)

			# verify the ping route
			ws = WebService(base_url, {'api_key':api_key})
			try:
				ping_results =ws.get('ping')
			except Exception as e:
				print "Couldn't Ping: " + e.message

			# we were able to ping, so grab solution states and save
			config_file.set('solution_states', ws.get('solutionStates'))