
class Autoloader:

	@staticmethod
	def importClass(module_name, class_name):
		try:
			m = __import__(module_name, globals(), locals())

			if class_name == None:
				return m

			else:
				return getattr(m, class_name)

		except Exception as e:
			print(e)
			return None