#!C:/Program Files/Python38/python.exe

import binascii
import mysql.connector
from datetime import datetime
import json

# open save file
filename = r'C:\Users\Aaron\AppData\Roaming\RetroArch\states\nhl94_updated.state'
with open(filename,'rb') as inputfile:
    content = inputfile.read()
    hexFile = binascii.hexlify(content).decode('utf-8')
    n = 2
    hexes = [(hexFile[i:i+n]) for i in range(0, len(hexFile), n)]

# connect to MySQL
mydb = mysql.connector.connect(
    host='localhost',
    user='nhl94',
    password='HpMZ6o6UMi',
    database='nhl94seasonreplay'
)
mycursor = mydb.cursor()

# get team ids
teams = {}
mycursor.execute("SELECT * FROM teams WHERE hexvalue = '{}'".format(hexes[49984]))
teams['home'] = mycursor.fetchone()
mycursor.execute("SELECT * FROM teams WHERE hexvalue = '{}'".format(hexes[49986]))
teams['away'] = mycursor.fetchone()

# get rosters from database
rosters = {}
for key, value in teams.items():
    mycursor.execute("SELECT * FROM players WHERE team_id = {} ORDER BY id ASC".format(teams[key][0]))
    rosters[key] = mycursor.fetchall()

# parse scoringsummary
scoringsummary = []
goal = {}
scorsummsize = int(hexes[50306],16)
for i in range(50308, 50308 + scorsummsize, 6):
    if hexes[i + 1][:1] == '0':
        period = 1
    elif hexes[i + 1][:1] == '4':
        period = 2
    elif hexes[i + 1][:1] == '8':
        period = 3
    else:
        period = 4
    timeelapsed = int(str(hexes[i + 1][1:]) + str(hexes[i]),16)
    goaltype = hexes[i + 3]
    if hexes[i + 3][:1] == '0':
        scoresummary_team = 'home'
        team_abbr = teams['home'][5]
    else:
        scoresummary_team = 'away'
        team_abbr = teams['away'][5]
    goalscorer = rosters[scoresummary_team][int(hexes[i + 2],16)][0]

    if hexes[i + 5] != 'ff':
        assist1 = rosters[scoresummary_team][int(hexes[i + 5],16)][0]
    else:
        assist1 = None
    if hexes[i + 4] != 'ff':
        assist2 = rosters[scoresummary_team][int(hexes[i + 4],16)][0]
    else:
        assist2 = None
    goal = {
        "hexindex": i,
        "period": period,
        "timeelapsed": timeelapsed,
        "team": scoresummary_team,
        "teamabbr": team_abbr,
        "goalscorer": goalscorer,
        "assist1": assist1,
        "assist2": assist2
    }
    scoringsummary.append(goal)

print(json.dumps(scoringsummary))
