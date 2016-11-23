/*jshint unused:false, scripturl:true */
var urls = [{
    name: 'scheme and domain',
    url: 'http://www.example.org',
    _url: 'http://www.example.org/',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: 'www.example.org',
      port: null,
      path: '/',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/',
      query: '',
      fragment: '',
      resource: '/',
      authority: 'www.example.org',
      userinfo: '',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/',
      filename: '',
      suffix: '',
      hash: '', // location.hash style
      search: '', // location.search style
      host: 'www.example.org',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'second level domain',
    url: 'http://www.example.co.uk',
    _url: 'http://www.example.co.uk/',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: 'www.example.co.uk',
      port: null,
      path: '/',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/',
      query: '',
      fragment: '',
      resource: '/',
      authority: 'www.example.co.uk',
      userinfo: '',
      subdomain: 'www',
      domain: 'example.co.uk',
      tld: 'co.uk',
      directory: '/',
      filename: '',
      suffix: '',
      hash: '', // location.hash style
      search: '', // location.search style
      host: 'www.example.co.uk',
      hostname: 'www.example.co.uk'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: true,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  },{
    name: 'qualified HTTP',
    url: 'http://www.example.org/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: 'www.example.org',
      port: null,
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'www.example.org',
      userinfo: '',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: 'www.example.org',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'funky suffix',
    url: 'http://www.example.org/some/directory/file.html-is-awesome?query=string#fragment',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: 'www.example.org',
      port: null,
      path: '/some/directory/file.html-is-awesome',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/some/directory/file.html-is-awesome',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html-is-awesome?query=string#fragment',
      authority: 'www.example.org',
      userinfo: '',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/some/directory',
      filename: 'file.html-is-awesome',
      suffix: '',
      hash: '#fragment',
      search: '?query=string',
      host: 'www.example.org',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'complete URL',
    url: 'scheme://user:pass@www.example.org:123/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'scheme',
      username: 'user',
      password: 'pass',
      hostname: 'www.example.org',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'scheme',
      username: 'user',
      password: 'pass',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'user:pass@www.example.org:123',
      userinfo: 'user:pass',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: 'www.example.org:123',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'badly encoded userinfo',
    url: 'scheme://user:pass:word@www.example.org/',
    _url: 'scheme://user:pass%3Aword@www.example.org/',
    parts: {
      protocol: 'scheme',
      username: 'user',
      password: 'pass:word',
      hostname: 'www.example.org',
      port: null,
      path: '/',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'scheme',
      username: 'user',
      password: 'pass:word',
      port: '',
      path: '/',
      query: '',
      fragment: '',
      resource: '/',
      authority: 'user:pass%3Aword@www.example.org',
      userinfo: 'user:pass%3Aword',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/',
      filename: '',
      suffix: '',
      hash: '',
      search: '',
      host: 'www.example.org',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'malformed email in userinfo',
    url: 'scheme://john@doe.com:pass:word@www.example.org/',
    _url: 'scheme://john%40doe.com:pass%3Aword@www.example.org/',
    parts: {
      protocol: 'scheme',
      username: 'john@doe.com',
      password: 'pass:word',
      hostname: 'www.example.org',
      port: null,
      path: '/',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'scheme',
      username: 'john@doe.com',
      password: 'pass:word',
      port: '',
      path: '/',
      query: '',
      fragment: '',
      resource: '/',
      authority: 'john%40doe.com:pass%3Aword@www.example.org',
      userinfo: 'john%40doe.com:pass%3Aword',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/',
      filename: '',
      suffix: '',
      hash: '',
      search: '',
      host: 'www.example.org',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'host-relative: URL',
    url: '/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: null,
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: '',
      username: '',
      password: '',
      port: '',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: '',
      hostname: ''
    },
    is: {
      urn: false,
      url: true,
      relative: true,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'path-relative: URL',
    url: '../some/directory/file.html?query=string#fragment',
    parts: {
      protocol: null,
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: '../some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: '',
      username: '',
      password: '',
      port: '',
      path: '../some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '../some/directory/file.html?query=string#fragment',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '../some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: '',
      hostname: ''
    },
    is: {
      urn: false,
      url: true,
      relative: true,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'missing scheme',
    url: 'user:pass@www.example.org:123/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'user',
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: 'pass@www.example.org:123/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'user',
      username: '',
      password: '',
      port: '',
      path: 'pass@www.example.org:123/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: 'pass@www.example.org:123/some/directory/file.html?query=string#fragment',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '',
      filename: '',
      suffix: '',
      hash: '#fragment',
      search: '?query=string',
      host: '',
      hostname: ''
    },
    is: {
      urn: true,
      url: false,
      relative: false,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'ignoring scheme',
    url: '://user:pass@example.org:123/some/directory/file.html?query=string#fragment',
    _url: '//user:pass@example.org:123/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: null,
      username: 'user',
      password: 'pass',
      hostname: 'example.org',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: '',
      username: 'user',
      password: 'pass',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'user:pass@example.org:123',
      userinfo: 'user:pass',
      subdomain: '',
      domain: 'example.org',
      tld: 'org',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: 'example.org:123',
      hostname: 'example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'scheme-relative URL',
    url: '//www.example.org/',
    parts: {
      protocol: null,
      username: null,
      password: null,
      hostname: 'www.example.org',
      port: null,
      path: '/',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: '',
      username: '',
      password: '',
      port: '',
      path: '/',
      query: '',
      fragment: '',
      resource: '/',
      authority: 'www.example.org',
      userinfo: '',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/',
      filename: '',
      suffix: '',
      hash: '',
      search: '',
      host: 'www.example.org',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv4',
    url: 'http://user:pass@123.123.123.123:123/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      hostname: '123.123.123.123',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'user:pass@123.123.123.123:123',
      userinfo: 'user:pass',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: '123.123.123.123:123',
      hostname: '123.123.123.123'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: true,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6',
    url: 'http://user:pass@fe80:0000:0000:0000:0204:61ff:fe9d:f156/some/directory/file.html?query=string#fragment',
    _url: 'http://user:pass@[fe80:0000:0000:0000:0204:61ff:fe9d:f156]/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      hostname: 'fe80:0000:0000:0000:0204:61ff:fe9d:f156',
      port: null,
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      port: '',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'user:pass@[fe80:0000:0000:0000:0204:61ff:fe9d:f156]',
      userinfo: 'user:pass',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: '[fe80:0000:0000:0000:0204:61ff:fe9d:f156]',
      hostname: 'fe80:0000:0000:0000:0204:61ff:fe9d:f156'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 with port',
    url: 'http://user:pass@[fe80:0000:0000:0000:0204:61ff:fe9d:f156]:123/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      hostname: 'fe80:0000:0000:0000:0204:61ff:fe9d:f156',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'user:pass@[fe80:0000:0000:0000:0204:61ff:fe9d:f156]:123',
      userinfo: 'user:pass',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: '[fe80:0000:0000:0000:0204:61ff:fe9d:f156]:123',
      hostname: 'fe80:0000:0000:0000:0204:61ff:fe9d:f156'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 brackets, port, file.ext',
    url: 'http://[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80/index.html',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: 'FEDC:BA98:7654:3210:FEDC:BA98:7654:3210',
      port: '80',
      path: '/index.html',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '80',
      path: '/index.html',
      query: '',
      fragment: '',
      resource: '/index.html',
      authority: '[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/',
      filename: 'index.html',
      suffix: 'html',
      hash: '',
      search: '',
      host: '[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80',
      hostname: 'FEDC:BA98:7654:3210:FEDC:BA98:7654:3210'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 brackets, file.ext',
    url: 'http://[1080:0:0:0:8:800:200C:417A]/index.html',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: '1080:0:0:0:8:800:200C:417A',
      port: null,
      path: '/index.html',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/index.html',
      query: '',
      fragment: '',
      resource: '/index.html',
      authority: '[1080:0:0:0:8:800:200C:417A]',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/',
      filename: 'index.html',
      suffix: 'html',
      hash: '',
      search: '',
      host: '[1080:0:0:0:8:800:200C:417A]',
      hostname: '1080:0:0:0:8:800:200C:417A'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 brackets ::1',
    url: 'http://[3ffe:2a00:100:7031::1]',
    _url: 'http://[3ffe:2a00:100:7031::1]/',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: '3ffe:2a00:100:7031::1',
      port: null,
      path: '/',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/',
      query: '',
      fragment: '',
      resource: '/',
      authority: '[3ffe:2a00:100:7031::1]',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/',
      filename: '',
      suffix: '',
      hash: '',
      search: '',
      host: '[3ffe:2a00:100:7031::1]',
      hostname: '3ffe:2a00:100:7031::1'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 brackets, file',
    url: 'http://[1080::8:800:200C:417A]/foo',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: '1080::8:800:200C:417A',
      port: null,
      path: '/foo',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/foo',
      query: '',
      fragment: '',
      resource: '/foo',
      authority: '[1080::8:800:200C:417A]',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/',
      filename: 'foo',
      suffix: '',
      hash: '',
      search: '',
      host: '[1080::8:800:200C:417A]',
      hostname: '1080::8:800:200C:417A'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 IPv4 brackets, path',
    url: 'http://[::192.9.5.5]/ipng',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: '::192.9.5.5',
      port: null,
      path: '/ipng',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/ipng',
      query: '',
      fragment: '',
      resource: '/ipng',
      authority: '[::192.9.5.5]',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/',
      filename: 'ipng',
      suffix: '',
      hash: '',
      search: '',
      host: '[::192.9.5.5]',
      hostname: '::192.9.5.5'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 mask IPv4 brackets, port, file.ext',
    url: 'http://[::FFFF:129.144.52.38]:80/index.html',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: '::FFFF:129.144.52.38',
      port: '80',
      path: '/index.html',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '80',
      path: '/index.html',
      query: '',
      fragment: '',
      resource: '/index.html',
      authority: '[::FFFF:129.144.52.38]:80',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/',
      filename: 'index.html',
      suffix: 'html',
      hash: '',
      search: '',
      host: '[::FFFF:129.144.52.38]:80',
      hostname: '::FFFF:129.144.52.38'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IPv6 brackets',
    url: 'http://[2010:836B:4179::836B:4179]',
    _url: 'http://[2010:836B:4179::836B:4179]/',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: '2010:836B:4179::836B:4179',
      port: null,
      path: '/',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/',
      query: '',
      fragment: '',
      resource: '/',
      authority: '[2010:836B:4179::836B:4179]',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/',
      filename: '',
      suffix: '',
      hash: '',
      search: '',
      host: '[2010:836B:4179::836B:4179]',
      hostname: '2010:836B:4179::836B:4179'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: false,
      sld: false,
      ip: true,
      ip4: false,
      ip6: true,
      idn: false,
      punycode: false
    }
  }, {
    name: 'IDN (punycode)',
    url: 'http://user:pass@xn--exmple-cua.org:123/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      hostname: 'xn--exmple-cua.org',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'user:pass@xn--exmple-cua.org:123',
      userinfo: 'user:pass',
      subdomain: '',
      domain: 'xn--exmple-cua.org',
      tld: 'org',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: 'xn--exmple-cua.org:123',
      hostname: 'xn--exmple-cua.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: true
    }
  }, {
    name: 'IDN',
    url: 'http://user:pass@exämple.org:123/some/directory/file.html?query=string#fragment',
    parts: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      hostname: 'exämple.org',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment'
    },
    accessors: {
      protocol: 'http',
      username: 'user',
      password: 'pass',
      port: '123',
      path: '/some/directory/file.html',
      query: 'query=string',
      fragment: 'fragment',
      resource: '/some/directory/file.html?query=string#fragment',
      authority: 'user:pass@exämple.org:123',
      userinfo: 'user:pass',
      subdomain: '',
      domain: 'exämple.org',
      tld: 'org',
      directory: '/some/directory',
      filename: 'file.html',
      suffix: 'html',
      hash: '#fragment',
      search: '?query=string',
      host: 'exämple.org:123',
      hostname: 'exämple.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: true,
      punycode: false
    }
  }, {
    name: 'file://',
    url: 'file:///foo/bar/baz.html',
    parts: {
      protocol: 'file',
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: '/foo/bar/baz.html',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'file',
      username: '',
      password: '',
      port: '',
      path: '/foo/bar/baz.html',
      query: '',
      fragment: '',
      resource: '/foo/bar/baz.html',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/foo/bar',
      filename: 'baz.html',
      suffix: 'html',
      hash: '',
      search: '',
      host: '',
      hostname: ''
    },
    is: {
      urn: false,
      url: true,
      relative: true,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'file://example.org:123',
    url: 'file://example.org:123/foo/bar/baz.html',
    parts: {
      protocol: 'file',
      username: null,
      password: null,
      hostname: 'example.org',
      port: '123',
      path: '/foo/bar/baz.html',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'file',
      username: '',
      password: '',
      port: '123',
      path: '/foo/bar/baz.html',
      query: '',
      fragment: '',
      resource: '/foo/bar/baz.html',
      authority: 'example.org:123',
      userinfo: '',
      subdomain: '',
      domain: 'example.org',
      tld: 'org',
      directory: '/foo/bar',
      filename: 'baz.html',
      suffix: 'html',
      hash: '',
      search: '',
      host: 'example.org:123',
      hostname: 'example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'file:// Windows-Drive-Letter',
    url: 'file:///C:/WINDOWS/foo.txt',
    parts: {
      protocol: 'file',
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: '/C:/WINDOWS/foo.txt',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'file',
      username: '',
      password: '',
      port: '',
      path: '/C:/WINDOWS/foo.txt',
      query: '',
      fragment: '',
      resource: '/C:/WINDOWS/foo.txt',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/C:/WINDOWS',
      filename: 'foo.txt',
      suffix: 'txt',
      hash: '',
      search: '',
      host: '',
      hostname: ''
    },
    is: {
      urn: false,
      url: true,
      relative: true,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'file://example.org/ Windows-Drive-Letter',
    url: 'file://example.org/C:/WINDOWS/foo.txt',
    parts: {
      protocol: 'file',
      username: null,
      password: null,
      hostname: 'example.org',
      port: null,
      path: '/C:/WINDOWS/foo.txt',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'file',
      username: '',
      password: '',
      port: '',
      path: '/C:/WINDOWS/foo.txt',
      query: '',
      fragment: '',
      resource: '/C:/WINDOWS/foo.txt',
      authority: 'example.org',
      userinfo: '',
      subdomain: '',
      domain: 'example.org',
      tld: 'org',
      directory: '/C:/WINDOWS',
      filename: 'foo.txt',
      suffix: 'txt',
      hash: '',
      search: '',
      host: 'example.org',
      hostname: 'example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'file://localhost/ Windows-Drive-Letter with pipe',
    url: 'file://localhost/C|/WINDOWS/foo.txt',
    parts: {
      protocol: 'file',
      username: null,
      password: null,
      hostname: 'localhost',
      port: null,
      path: '/C|/WINDOWS/foo.txt',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'file',
      username: '',
      password: '',
      port: '',
      path: '/C|/WINDOWS/foo.txt',
      query: '',
      fragment: '',
      resource: '/C|/WINDOWS/foo.txt',
      authority: 'localhost',
      userinfo: '',
      subdomain: '',
      domain: 'localhost',
      tld: 'localhost',
      directory: '/C|/WINDOWS',
      filename: 'foo.txt',
      suffix: 'txt',
      hash: '',
      search: '',
      host: 'localhost',
      hostname: 'localhost'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'Path containing @',
    url: 'http://www.example.org/@foobar',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: 'www.example.org',
      port: null,
      path: '/@foobar',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/@foobar',
      query: '',
      fragment: '',
      resource: '/@foobar',
      authority: 'www.example.org',
      userinfo: '',
      subdomain: 'www',
      domain: 'example.org',
      tld: 'org',
      directory: '/',
      filename: '@foobar',
      suffix: '',
      hash: '', // location.hash style
      search: '', // location.search style
      host: 'www.example.org',
      hostname: 'www.example.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'mailto:',
    url: 'mailto:hello@example.org?subject=hello',
    _url: 'mailto:hello@example.org?subject=hello',
    parts: {
      protocol: 'mailto',
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: 'hello@example.org',
      query: 'subject=hello',
      fragment: null
    },
    accessors: {
      protocol: 'mailto',
      username: '',
      password: '',
      port: '',
      path: 'hello@example.org',
      query: 'subject=hello',
      fragment: '',
      resource: 'hello@example.org?subject=hello',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '',
      filename: '',
      suffix: '',
      hash: '',
      search: '?subject=hello',
      host: '',
      hostname: ''
    },
    is: {
      urn: true,
      url: false,
      relative: false,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'magnet:',
    url: 'magnet:?xt=urn:btih:f8c020dac7a083defda1769a1196a13facc38ef6&dn=Linux+64x+server+11.10+Pt+Pt&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.ccc.de%3A80',
    _url: 'magnet:?xt=urn:btih:f8c020dac7a083defda1769a1196a13facc38ef6&dn=Linux+64x+server+11.10+Pt+Pt&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.ccc.de%3A80',
    parts: {
      protocol: 'magnet',
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: '',
      query: 'xt=urn:btih:f8c020dac7a083defda1769a1196a13facc38ef6&dn=Linux+64x+server+11.10+Pt+Pt&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.ccc.de%3A80',
      fragment: null
    },
    accessors: {
      protocol: 'magnet',
      username: '',
      password: '',
      port: '',
      path: '',
      query: 'xt=urn:btih:f8c020dac7a083defda1769a1196a13facc38ef6&dn=Linux+64x+server+11.10+Pt+Pt&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.ccc.de%3A80',
      fragment: '',
      resource: '?xt=urn:btih:f8c020dac7a083defda1769a1196a13facc38ef6&dn=Linux+64x+server+11.10+Pt+Pt&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.ccc.de%3A80',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '',
      filename: '',
      suffix: '',
      hash: '',
      search: '?xt=urn:btih:f8c020dac7a083defda1769a1196a13facc38ef6&dn=Linux+64x+server+11.10+Pt+Pt&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.ccc.de%3A80',
      host: '',
      hostname: ''
    },
    is: {
      urn: true,
      url: false,
      relative: false,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'javascript:',
    url: 'javascript:alert("hello world");',
    _url: 'javascript:alert("hello world");',
    parts: {
      protocol: 'javascript',
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: 'alert("hello world");',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'javascript',
      username: '',
      password: '',
      port: '',
      path: 'alert("hello world");',
      query: '',
      fragment: '',
      resource: 'alert("hello world");',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '',
      filename: '',
      suffix: '',
      hash: '',
      search: '',
      host: '',
      hostname: ''
    },
    is: {
      urn: true,
      url: false,
      relative: false,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'colon in path',
    url: 'http://en.wikipedia.org/wiki/Help:IPA',
    _url: 'http://en.wikipedia.org/wiki/Help:IPA',
    parts: {
      protocol: 'http',
      username: null,
      password: null,
      hostname: 'en.wikipedia.org',
      port: null,
      path: '/wiki/Help:IPA',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: 'http',
      username: '',
      password: '',
      port: '',
      path: '/wiki/Help:IPA',
      query: '',
      fragment: '',
      resource: '/wiki/Help:IPA',
      authority: 'en.wikipedia.org',
      userinfo: '',
      subdomain: 'en',
      domain: 'wikipedia.org',
      tld: 'org',
      directory: '/wiki',
      filename: 'Help:IPA',
      suffix: '',
      hash: '',
      search: '',
      host: 'en.wikipedia.org',
      hostname: 'en.wikipedia.org'
    },
    is: {
      urn: false,
      url: true,
      relative: false,
      name: true,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'colon in path without protocol',
    url: '/wiki/Help:IPA',
    _url: '/wiki/Help:IPA',
    parts: {
      protocol: null,
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: '/wiki/Help:IPA',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: '',
      username: '',
      password: '',
      port: '',
      path: '/wiki/Help:IPA',
      query: '',
      fragment: '',
      resource: '/wiki/Help:IPA',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/wiki',
      filename: 'Help:IPA',
      suffix: '',
      hash: '',
      search: '',
      host: '',
      hostname: ''
    },
    is: {
      urn: false,
      url: true,
      relative: true,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
    name: 'colon dash dash in path without protocol',
    url: '/foo/xy://bar',
    _url: '/foo/xy://bar',
    parts: {
      protocol: null,
      username: null,
      password: null,
      hostname: null,
      port: null,
      path: '/foo/xy://bar',
      query: null,
      fragment: null
    },
    accessors: {
      protocol: '',
      username: '',
      password: '',
      port: '',
      path: '/foo/xy://bar',
      query: '',
      fragment: '',
      resource: '/foo/xy://bar',
      authority: '',
      userinfo: '',
      subdomain: '',
      domain: '',
      tld: '',
      directory: '/foo/xy:/', // sanitized empty directory!
      filename: 'bar',
      suffix: '',
      hash: '',
      search: '',
      host: '',
      hostname: ''
    },
    is: {
      urn: false,
      url: true,
      relative: true,
      name: false,
      sld: false,
      ip: false,
      ip4: false,
      ip6: false,
      idn: false,
      punycode: false
    }
  }, {
      name: 'colon in path',
      url: 'http://www.example.org:8080/hello:world',
      parts: {
        protocol: 'http',
        username: null,
        password: null,
        hostname: 'www.example.org',
        port: '8080',
        path: '/hello:world',
        query: null,
        fragment: null
      },
      accessors: {
        protocol: 'http',
        username: '',
        password: '',
        port: '8080',
        path: '/hello:world',
        query: '',
        fragment: '',
        resource: '/hello:world',
        authority: 'www.example.org:8080',
        userinfo: '',
        subdomain: 'www',
        domain: 'example.org',
        tld: 'org',
        directory: '/',
        filename: 'hello:world',
        suffix: '',
        hash: '', // location.hash style
        search: '', // location.search style
        host: 'www.example.org:8080',
        hostname: 'www.example.org'
      },
      is: {
        urn: false,
        url: true,
        relative: false,
        name: true,
        sld: false,
        ip: false,
        ip4: false,
        ip6: false,
        idn: false,
        punycode: false
      }
    }
];

