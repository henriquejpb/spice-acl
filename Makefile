testobj=.

install:
	composer install

unittest:
	@cd test/unit/ && phpunit --colors $(testobj)

ctags:
	ctags -R --language-force=PHP src/ test/unit/

phpdoc:
	phpdoc -d src/ -t docs/