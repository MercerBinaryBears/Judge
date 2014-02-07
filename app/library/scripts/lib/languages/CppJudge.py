import os
from LanguageJudge import LanguageJudge

class CppJudge(LanguageJudge):
	def getCompileString(self):
		return "g++ {0}".format(self.filename)

	def checkCompilePassed(self, output):
		return output == ''

	def getRunCommand(self):
		return "a.out"

	def cleanup(self):
		os.remove("a.out");
