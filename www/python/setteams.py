#!C:/Program Files/Python38/python.exe

import binascii
import mysql.connector
from datetime import datetime
from secrets import randbelow
import sys

# open save file
filename = r'C:\Users\Aaron\AppData\Roaming\RetroArch\states\nhl94_updated.state'
with open(filename,'rb') as inputfile:
    content = inputfile.read()
    hexFile = binascii.hexlify(content).decode('utf-8')
    n = 2
    hexes = [(hexFile[i:i+n]) for i in range(0, len(hexFile), n)]

# get the scheduleid
with open("www\currentgame.txt",'r') as inputfile:
    scheduleid = inputfile.read()

# connect to MySQL
mydb = mysql.connector.connect(
    host='localhost',
    user='nhl94',
    password='HpMZ6o6UMi',
    database='nhl94seasonreplay'
)
mycursor = mydb.cursor()

# get team ids
mycursor.execute("select h.hexvalue as home, a.hexvalue as away from schedule s join teams h on s.hometeam_id = h.id join teams a on s.awayteam_id = a.id where s.id = {}".format(scheduleid))
teams = mycursor.fetchone()

hexes[49984] = teams[0]
hexes[49986] = teams[1]

# randomize team. away teams lost 40% of the time, so using a 60/40 split
# randteam = randbelow(100)
randteam = int(sys.argv[1])
if randteam <= 60:
    hexes[49976] = '01'
else:
    hexes[49976] = '02'

# set goalies from arguments passed in
# home goalie
hexes[50948] = sys.argv[2]
# away goalie
hexes[51816] = sys.argv[3]

newhexes = []
for i in range(0,len(hexes)):
    newhexes.append(int(hexes[i],16))

with open(filename,'wb') as writefile:
    writefile.write(bytearray(newhexes))

print('{"playingas":"' + hexes[49976] + '", "homegoalie":"' + hexes[50948] + '", "awaygoalie":"' + hexes[51816] + '"}')
