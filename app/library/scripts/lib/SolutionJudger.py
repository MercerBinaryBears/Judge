import commands

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
		languageJudgeClassName = extension.capitalize() + 'Judge'

		# now, attempt to auto import it
		# (via http://stackoverflow.com/questions/4821104)
		module_name = 'libs.languages.' + languageJudgeClassName
		if self.debug:
			print 'Attempting to autoload',module_name

		module = __import__(module_name)
		return getattr(module, languageJudgeClassName)

	def compile(self):
		'''Attempts to compile the passed file. Returns a tuple with the compile result, and output'''

		if self.debug:
			print 'Attempting to compile... ',

		# get the status and output
		status, output = commands.getstatusoutput(self.judger.getCompileString())

		if debug:
			print 'Status {0}\nOutput:\n{1}'.format(status, output)


		# check the result with the class. TODO: just use the status code instead!
		did_succeed = self.judge.checkCompilePassed(output)

		return (did_succeed, output)

	def run(self, input_file='/dev/null', output_file='/dev/null'):
		'''Runs the compiled output from the passed file on the passed input file, redirected in the output file, returning the status code'''

		run_command += "{0} < {1} > {2}".format(self.judger.getRunCommand(), input_file, output_file)

		if debug:
			print "Command to run is " + run_command

		status, output = commands.getstatusoutput(run_command)

		return status, output

	def diff(self, program_output_file='/dev/null', expected_output_file='/dev/null'):
		'''Checks the output of a program with the correct output, returns a tuple with a boolean match, and the diff output'''

		if debug:
			print "Comparing program output {0} and judging output {1}".format(program_output_file, expected_output_file)

		status, output = commands.getstatusoutput('diff {0} {1}')

		return (output == output, output)