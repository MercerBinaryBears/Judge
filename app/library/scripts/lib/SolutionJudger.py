import os
import commands
from lib.Autoloader import Autoloader

class SolutionJudger(object):
	'''Judges a passed solution by autodetecting the language and loading the correct language judging class'''

	def __init__(self, filename=None, languageJudgeClass=None, debug=False):
		# Make sure that a file is actual provided to the class
		if filename == None:
			raise Exception('No Filename Provided to judge')
		self.filename = filename

		self.debug = debug

		# if no language judge is provided, attempt to autoload it
		if languageJudgeClass == None:
			languageJudgeClass = self._autoloadLanguageJudge(filename)

		#instantiate the class
		self.judger = languageJudgeClass(filename)

	def _autoloadLanguageJudge(self, filename):
		'''
		Given a filename, attempts to autoload the LanguageJudge subclass that parses this language
		'''

		# build the class name from the extension, by title casing: PyJudge, CJudge, CppJudge, JavaJudge, RubyJudge, and so on
		extension = filename.split('.')[-1]

		class_name = extension.capitalize() + 'Judge'
		module_name = "lib.languages.{0}".format(class_name)

		if self.debug:
			print('Loading module: ',)
			print(class_name)

		return Autoloader.importClass(module_name, class_name)


	def compile(self):
		'''Attempts to compile the passed file. Returns a tuple with the compile result, and output'''

		if self.debug:
			print('Attempting to compile... ',)

		# get the status and output
		status, output = commands.getstatusoutput(self.judger.getCompileString())

		if self.debug:
			print('Status {0}\nOutput:\n{1}'.format(status, output))


		# check the result with the class. TODO: just use the status code instead!
		did_succeed = self.judger.checkCompilePassed(output)

		return (did_succeed, output)

	def run(self, input_file='judge.in', output_file='team.out'):
		'''Runs the compiled output from the passed file on the passed input file, redirected in the output file, returning the status code'''

		run_command = "{0} < {1} > {2}".format(self.judger.getRunCommand(), input_file, output_file)

		if self.debug:
			print("Command to run is " + run_command)

		# assign this output file to variable, so when they run a diff, we don't have to pass it
		self.program_output_file = output_file

		status, output = commands.getstatusoutput(run_command)

		return status==0, output

	def diff(self, expected_output_file='judge.out', program_output_file='team.out'):
		'''Checks the output of a program with the correct output, returns a tuple with a boolean match, and the diff output'''

		if program_output_file != None:
			self.program_output_file = program_output_file

		if self.debug:
			print("Comparing program output {0} and judging output {1}".format(self.program_output_file, expected_output_file))


		# instead of simply running diff, we'll trim extra spaces off the ends
		build_sed_command = lambda filename : "<(cat {0} | sed 's/ *$//; s/^ *//')".format(filename)
		
		trimmed_team_output = build_sed_command(self.program_output_file)
		trimmed_judge_output = build_sed_command(expected_output_file)

		status, output = commands.getstatusoutput('diff -y --suppress-common-lines {0} {1}'.format(
			trimmed_team_output, trimmed_judge_output))

		return (output == '', output)

	def cleanup(self):
		'''Cleans up any left over files'''
		try:
			os.remove('team.out')
		except:
			pass
		self.judger.cleanup();
