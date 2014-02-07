from lib.util.ConfigFile import ConfigFile
from lib.util.WebService import WebService
from lib.judge_commands.Command import Command

from zipfile import ZipFile
import os

class ClaimCommand(Command):
	def execute(self, arguments):
		with ConfigFile() as config_file:
			base_url = config_file.get('base_url')
			api_key = config_file.get('api_key')

			ws = WebService(base_url, {'api_key':api_key})

			# if we haven't claimed a solution, attempt to claim one
			solution = config_file.get('solution')

			if solution == None:
				# get all solutions
				solutions = ws.get('solutions')

				if len(solutions['data']) == 0:
					print('No solutions to claim. Check back later')
					return

				# loop over solutions, attempting to claim
				claim_results = None
				for solution in solutions['data']:
					claim_results = ws.get('solutions/{0}/claim'.format(solution['id']) )

					# check for a 200
					if claim_results['code'] == 200:
						break

				# now save the state
				print('Claimed ' + solution['id'])
				config_file.set('solution', solution)
			else:
				print('Already have a claimed solution: ' + solution['id'])

			# write solution package to a zip
			print('Downloading solution package... ',)
			solution_package_contents = ws.get('solutions/{0}/package'.format(solution['id']), raw=True)
			solution_package = open('solution_package.zip', 'w+b')
			solution_package.write(solution_package_contents)
			solution_package.close()

			# unzip to the working directory, writing the file names to the config file
			print('Unpacking Zip File... ',)
			solution_package = ZipFile('solution_package.zip', 'r')
			config_file.set('solution_package_files', solution_package.namelist())
			solution_package.extractall()
			solution_package.close()

			# remove the zip file, since we no longer need it
			print('Removing Zip... ',)
			os.remove('solution_package.zip')

			print('Done!')
