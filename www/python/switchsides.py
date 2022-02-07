#!C:/Program Files/Python38/python.exe

import binascii
import mysql.connector

# open save file
filename = r'C:\Users\Aaron\AppData\Roaming\RetroArch\states\nhl94_updated.state'
with open(filename,'rb') as inputfile:
    content = inputfile.read()
    hexFile = binascii.hexlify(content).decode('utf-8')
    n = 2
    hexes = [(hexFile[i:i+n]) for i in range(0, len(hexFile), n)]

if hexes[49976] == '01':
    hexes[49976] = '02'
else:
    hexes[49976] = '01'
# 2 - (hexes[49976] - 1)

newhexes = []
for i in range(0,len(hexes)):
    newhexes.append(int(hexes[i],16))

switchedfile = bytearray(newhexes)
with open(filename,'wb') as writefile:
    writefile.write(switchedfile)

# connect to MySQL
mydb = mysql.connector.connect(
    host='localhost',
    user='nhl94',
    password='HpMZ6o6UMi',
    database='nhl94seasonreplay'
)
mycursor = mydb.cursor()

# get team ids
teams = []
mycursor.execute("SELECT * FROM teams WHERE hexvalue = '{}'".format(hexes[49984]))
teams.append(mycursor.fetchone())
mycursor.execute("SELECT * FROM teams WHERE hexvalue = '{}'".format(hexes[49986]))
teams.append(mycursor.fetchone())

# print('You are now playing as the {}'.format(teams[int(hexes[49976],16) - 1][4]))
print(teams[int(hexes[49976],16) - 1][5])
