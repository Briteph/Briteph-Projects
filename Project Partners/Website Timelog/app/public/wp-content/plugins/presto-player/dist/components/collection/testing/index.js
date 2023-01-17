export function setResponses(args, page) {
  page.on('response', response => {
    if (response.url().includes('localhost')) {
      page.removeAllListeners('request');
      page.on('request', request => {
        let data;
        args.forEach(arg => {
          if (request.url().includes(arg.path)) {
            data = arg.data;
          }
        });
        if (data) {
          return request.respond(data);
        }
        else {
          request.continue();
        }
      });
    }
  });
}
