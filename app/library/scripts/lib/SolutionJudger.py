class SolutionJudger(object):
	'''Judges a passed solution by autodetecting the language and loading the correct language judging class'''

	def __init__(self, filename=None, languageJudgeClass=None):
		# Make sure that a file is actual provided to the class
		if filename == None:
			raise Exception('No Filename Provided to judge')
		self.filename = filename

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
		module = __import__('libs.languages.' + languageJudgeClassName)
		return getattr(module, languageJudgeClassName)