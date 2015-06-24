default['webpagetest']['user'] = 'webpagetest'
default['webpagetest']['group'] = 'webpagetest'
default['webpagetest']['system_user'] = 'webpagetest'
default['webpagetest']['system_group'] = 'webpagetest'
default['webpagetest']['root_directory'] = "/home/webpagetest/www/"
default['webpagetest']['apache_host'] = '127.0.0.1'
default['webpagetest']['apache_port'] = '80'

override['ffmpeg']['compile_flags'] = [
  '--disable-debug',
  '--enable-pthreads',
  '--enable-nonfree',
  '--enable-gpl',
  '--enable-libx264',
  '--disable-indev=jack',
  '--enable-libfaac',
  '--enable-libmp3lame',
  '--enable-libtheora',
  '--enable-libvorbis',
  '--enable-libvpx',
  '--enable-libxvid',
  '--enable-libfaad'
]
override['ffmpeg']['git_revision'] = '620197d1ffea20e9168372c354438f1c1e926ecd' #version 2.7.1
override['yasm']['install_method'] = ':source'

