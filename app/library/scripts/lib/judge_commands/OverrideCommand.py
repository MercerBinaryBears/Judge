from lib.util.ConfigFile import ConfigFile
from lib.util.WebService import WebService
from lib.judge_commands.Command import Command

class OverrideCommand(Command):
	def execute(self, arguments):
		with ConfigFile() as config_file:

			# get the array of solution states
			solution_states = config_file.get('solution_states')
			if solution_states == None:
				print("You have not setup your judge environment. Please set it up with 'judge setup'")
				return

			# make sure that we have claimed a solution
			if config_file.get('solution') == None:
				print("You have not claimed a solution yet")
				return

			# now show all of the choices, and prompt the user
			for k,solution_state in enumerate(solution_states):
				print("{0}) {1}".format(k+1, solution_state['name']))

			choices = range(1, len(solution_states)+1)

			user_input = None
			while user_input == None or user_input not in choices:
				try:
					user_input = input('New Solution State (ctrl-C to cancel): ')
				except:
					print('Cancelling')
					return

			chosen_state = solution_states[user_input-1]

			print("Updating judge result to be {0}".format(chosen_state['name']))

			config_file.set('judge_result', chosen_state['id'])