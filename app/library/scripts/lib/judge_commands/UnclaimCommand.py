from lib.util.ConfigFile import ConfigFile
from lib.util.WebService import WebService
from lib.judge_commands.Command import Command
import os

class UnclaimCommand(Command):
	def execute(self, arguments):
		with ConfigFile() as config_file:
			# check that they have actually claimed a solution first!
			solution = config_file.get('solution')
			if solution == None:
				print('No solution has been claimed')
				return

			# remove files for judging
			print('Removing files... ',)
			for filename in config_file.get('solution_package_files'):
				os.remove(filename)

			print('Unclaiming via webservice... ',)

			base_url = config_file.get('base_url')
			api_key = config_file.get('api_key')

			ws = WebService(base_url, {'api_key':api_key})
			result = ws.get("solutions/{0}/unclaim".format(solution['id']))

			if result['code'] != 200:
				print('Failed: ',)
				print(result['data'])
				return

			# erase the config file
			config_file.set('solution', None)
			config_file.set('judge_result', None)
			config_file.set('solution_package_files', None)