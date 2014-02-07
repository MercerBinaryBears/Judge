from lib.util.ConfigFile import ConfigFile
from lib.util.WebService import WebService
from lib.judge_commands.Command import Command
import os

class PushCommand(Command):
	def execute(self, arguments):
		with ConfigFile() as config_file:

			# get the solution you are judging
			solution = config_file.get('solution')
			if solution == None:
				print('You have not claimed a solution')
				return

			# get the solution state chosen for this problem
			solution_state_id = config_file.get('judge_result')
			if solution_state_id == None:
				print("You haven't judged your problem yet")
				return

			# post to the web service the updated value
			base_url = config_file.get('base_url')
			api_key = config_file.get('api_key')

			ws = WebService(base_url, {'api_key':api_key})

			result = ws.post('solutions/{0}'.format(solution['id']), {'solution_state_id':solution_state_id})

			if result['code'] != 200:
				print('Failed to update: ',)
				print(result)
				return

			print('Success!')

			print('Removing files... ',)
			for filename in config_file.get('solution_package_files'):
				os.remove(filename)

			# now, wipe the current solution and solution state
			config_file.set('solution', None)
			config_file.set('judge_result', None)
			config_file.set('solution_package_files', None)
