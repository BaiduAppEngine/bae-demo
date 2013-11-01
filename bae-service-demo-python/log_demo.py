import logging
from baelog import handlers

def app(environ, start_response):
    status = '200 OK'
    headers = [('Content-type', 'text/html')]
    start_response(status, headers)
    
    handler = handlers.BaeLogHandler("apikey", "secretkey", True)
    logger = logging.getLogger()
    logger.addHandler(handler)
    
    logger.debug("debug message")
    logger.info("info message")
    logger.warning("warning message")
    logger.fatal("fatal message")
    logger.log(12, "trace message")
    
    try:
        raise Exception("exception")
    except:
        logger.exception("exception message")

    return "logging..."

from bae.core.wsgi import WSGIApplication
application = WSGIApplication(app)

