from lib.util.ConfigFile import ConfigFile
from lib.util.WebService import WebService
from lib.judge_commands.Command import Command
from lib.SolutionJudger import SolutionJudger

class JudgeCommand(Command):
	def _firstSolutionStateMatch(self, solution_states, query_text):
		L = lambda ss : ss['name'].lower().find(query_text) > -1
		return filter(L, solution_states)[0]

	def execute(self, arguments):
		with ConfigFile() as config_file:

			# get the solution states from the config file
			solution_states = config_file.get('solution_states')
			if solution_states == None:
				print("You haven't setup your judging station yet. Please run 'judge setup'")
				return

			# get the solution code file
			files = config_file.get('solution_package_files')
			if files == None:
				print('No Solution has been claimed')
				return

			code_file = filter(lambda filename : not filename.endswith('in') and not filename.endswith('out'), files)[0]

			# Found code file, now we start the judging process
			judger = SolutionJudger(code_file, debug=True)

			# check for compile errors
			did_compile, output = judger.compile()
			if not did_compile:
				print("Compile error failed, setting judge result to be compile error")
				compile_error_state = self._firstSolutionStateMatch(solution_states, 'compile')
				config_file.set('judge_result', compile_error_state['id'])
				judger.cleanup()
				return

			# run checking for runtime errors
			did_run, output = judger.run()
			if not did_run:
				print("Run failed, setting judge result to be a runtime error")
				runtime_error_state = self._firstSolutionStateMatch(solution_states, 'runtime')
				config_file.set('judge_result', runtime_error_state['id'])
				judger.cleanup()
				return

			# now run the diff
			diff_match, output = judger.diff()
			if not diff_match:
				print('-'*80)
				print(output)
				print('-'*80 + "\n\n")
				print("Diff mismatch, setting judge result to be wrong answer. Override via the 'judge override' command")
				wrong_error_state = self._firstSolutionStateMatch(solution_states, 'wrong')
				config_file.set('judge_result', wrong_error_state['id'])
				judger.cleanup()
				return

			print("Perfect match, judging correct")
			correct_error_state = self._firstSolutionStateMatch(solution_states, 'correct')
			config_file.set('judge_result', correct_error_state['id'])
			judger.cleanup()

