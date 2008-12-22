::// Fast/Low quality
::bin\mencoder %1 -o "flv/%~n1.flv" -of lavf -ovc lavc -oac lavc -lavcopts vcodec=flv:vbitrate=250:autoaspect:acodec=mp3:abitrate=56 -vf scale=320:240 -srate 22050 -af lavcresample=22050

::// x3 Slower/High quality
::bin\mencoder %1 -o "flv/%1.flv" -of lavf -ovc lavc -oac lavc -lavcopts vcodec=flv:vbitrate=250:autoaspect:mbd=2:mv0:trell:v4mv:cbp:last_pred=3:predia=2:dia=2:precmp=2:cmp=2:subcmp=2:preme=2:turbo:acodec=mp3:abitrate=56 -vf scale=320:240 -srate 22050 -af lavcresample=22050

::i
::bin\mencoder %1 -i "flv/%1.flv"

::bin\mencoder %1 -of mpeg -oac lavc -lavcopts acodec=mp2:abitrate=192 -af resample=44100:0:0 -ovc lavc -lavcopts vcodec=%mpeg%:vbitrate=%bitrate% -vf scale=%~2:%~3,harddup -ofps 25 -ss %startpos% -endpos %endpos% -o "videos/%~n1%prefix%.mpg"


::bin\mencoder.exe %1 -vf harddup -ofps 25 -of lavf -lavfopts format=flv -ovc lavc -lavcopts vcodec=flv:vbitrate=250:vqmin=2:vqmax=31:mbd=1:subq=8 -af resample=44100:0:0 -oac mp3lame -lameopts cbr:br=64:aq=2:highpassfreq=-1:lowpassfreq=-1 -o "flv/%1.flv"

::bin\mencoder.exe %1 -vf scale=470:320 -ofps 25 -of lavf -lavfopts format=flv -ovc lavc -lavcopts vcodec=flv:vbitrate=250:vqmin=2:vqmax=31:mbd=1:subq=8 -af resample=44100:0:0 -oac mp3lame -lameopts cbr:br=64:aq=2:highpassfreq=-1:lowpassfreq=-1 -o "flv/%1.flv"

bin\mencoder.exe %1 -vf scale=470:320 -ofps 25 -of lavf -lavfopts format=flv -ovc lavc -lavcopts vcodec=flv:vbitrate=500:vqmin=2:vqmax=26:mbd=2:subq=8 -af resample=44100:0:0 -oac mp3lame -lameopts cbr:br=64:aq=2:highpassfreq=-1:lowpassfreq=-1 -o "flv/%~n1.flv"
