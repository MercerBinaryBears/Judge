from lib.util.ConfigFile import ConfigFile
from lib.util.WebService import WebService
from lib.judge_commands.Command import Command

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
				print("Couldn't Ping: " + e.message)

			# we were able to ping, so grab solution states and save
			solution_states_result = ws.get('solutionStates')

			if solution_states_result['code'] != 200:
				print('Failed getting Solution states: ' + str(solution_states_result['code']))

			config_file.set('solution_states', solution_states_result['data'])