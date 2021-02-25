var http = require("http"),
    url = require("url"),
    path = require("path"),
    fs = require("fs");

var config = {
    port: 9000,
    rootpath: process.cwd()
};

var patters = [
    {
        config: 'port',
        pattern: '^--port=(\\d+)$'
    },
    {
        config: 'rootpath',
        pattern: '^--rootpath=([A-z0-9\\/\\.\\-\\_]+)$',
        setter: function (rootpath) {
            this.rootpath = path.join(process.cwd(), rootpath);
        }
    }
];

function setter() {
    var self = this,
        args = arguments;
    if (self.hasOwnProperty('setter'))
        return self.setter.apply(config, args);
    Object
        .keys(args)
        .forEach(function (index) {
            config[self.config] = args[index];
        });
}

(process.argv)
    .slice(2)
    .forEach(function (token) {
        patters.forEach(function (rule) {
            var regexp = new RegExp(rule.pattern, 'g'),
                matches = regexp.exec(token);
            if (!matches)
                return;
            setter.apply(rule, matches.splice(1));
        });
    });

var server = http
    .createServer(function (request, response) {
        var uri = url.parse(request.url).pathname,
            filename = path.join(config.rootpath, uri);

        fs.exists(filename, function (exists) {
            if (!exists) {
                response.writeHead(404, {"Content-Type": "text/plain"});
                response.write("404 Not Found\n");
                response.end();
                return;
            }

            if (fs.statSync(filename).isDirectory())
                filename = path.join(filename, './index.html');

            fs.readFile(filename, "binary", function (error, filecontent) {
                if (error) {
                    response.writeHead(500, {"Content-Type": "text/plain"});
                    response.write(error + "\n");
                    response.end();
                    return;
                }

                response.writeHead(200);
                response.write(filecontent, "binary");
                response.end();
            });
        });
    })
    .listen(parseInt(config.port, 10));

console.log("Static file server running at\n  => http://localhost:" + config.port + "/\nCTRL + C to shutdown");