from LanguageJudge import LanguageJudge

class CJudge(LanguageJudge):
	def getCompileString(self):
		return "gcc {0}".format(self.filename)

	def checkCompilePassed(self, output):
		return output == ''

	def getRunCommand(self):
		return "a.out"