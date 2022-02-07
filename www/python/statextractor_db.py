#!C:/Program Files/Python38/python.exe

import binascii
import mysql.connector
from datetime import datetime

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

# get the scheduleid
with open("www\currentgame.txt",'r') as inputfile:
    scheduleid = inputfile.read()

# insert into games table (game stats)
sql = "INSERT INTO games (schedule_id, team_id, goals, shots, ppgoals, ppattempts, ppseconds, ppshots, shgoals, breakawayattempts, breakawaygoals, onetimerattempts, onetimergoals, penaltyshotattempts, penaltyshotgoals, faceoffswon, bodychecks, penalties, pim, attackzoneseconds, passattempts, passsuccess, insert_datetime) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
now = datetime.now()
insert_datetime = now.strftime("%Y-%m-%d %H:%M:%S")
val = [
    (scheduleid,teams['home'][0],int(str(hexes[50923]) + str(hexes[50922]),16),int(str(hexes[50911]) + str(hexes[50910]),16),int(str(hexes[50913]) + str(hexes[50912]),16),int(str(hexes[50915]) + str(hexes[50914]),16),int(str(hexes[51761]) + str(hexes[51760]),16),int(str(hexes[51763]) + str(hexes[51762]),16),int(str(hexes[51765]) + str(hexes[51764]),16),int(str(hexes[51767]) + str(hexes[51766]),16),int(str(hexes[51769]) + str(hexes[51768]),16),int(str(hexes[51771]) + str(hexes[51770]),16),int(str(hexes[51773]) + str(hexes[51772]),16),int(str(hexes[51775]) + str(hexes[51774]),16),int(str(hexes[51777]) + str(hexes[51776]),16),int(str(hexes[50925]) + str(hexes[50924]),16),int(str(hexes[50927]) + str(hexes[50926]),16),int(str(hexes[50917]) + str(hexes[50916]),16),int(str(hexes[50919]) + str(hexes[50918]),16),int(str(hexes[50921]) + str(hexes[50920]),16),int(str(hexes[50929]) + str(hexes[50928]),16),int(str(hexes[50931]) + str(hexes[50930]),16),insert_datetime),
    (scheduleid,teams['away'][0],int(str(hexes[51791]) + str(hexes[51790]),16),int(str(hexes[51779]) + str(hexes[51778]),16),int(str(hexes[51781]) + str(hexes[51780]),16),int(str(hexes[51783]) + str(hexes[51782]),16),int(str(hexes[52629]) + str(hexes[52628]),16),int(str(hexes[52631]) + str(hexes[52630]),16),int(str(hexes[52633]) + str(hexes[52632]),16),int(str(hexes[52635]) + str(hexes[52634]),16),int(str(hexes[52637]) + str(hexes[52636]),16),int(str(hexes[52639]) + str(hexes[52638]),16),int(str(hexes[52641]) + str(hexes[52640]),16),int(str(hexes[52643]) + str(hexes[52642]),16),int(str(hexes[52645]) + str(hexes[52644]),16),int(str(hexes[51793]) + str(hexes[51792]),16),int(str(hexes[51795]) + str(hexes[51794]),16),int(str(hexes[51785]) + str(hexes[51784]),16),int(str(hexes[51787]) + str(hexes[51786]),16),int(str(hexes[51789]) + str(hexes[51788]),16),int(str(hexes[51797]) + str(hexes[51796]),16),int(str(hexes[51799]) + str(hexes[51798]),16),insert_datetime)
]
mycursor.executemany(sql, val)
mydb.commit()
games = {}
games['home'] = mycursor.lastrowid
games['away'] = mycursor.lastrowid + 1
# print('Game Stats successfully uploaded')

# insert into periodstats table
sql = "INSERT INTO periodstats (game_id, period, goals, shots) VALUES (%s, %s, %s, %s)"
periodstats = []
# periodrange = int(hexes[50294],16) + 1
periodrange = 4
for p in range(0,periodrange):
    periodstats.append([games['home'],p + 1,int(str(hexes[51745 + p * 2]) + str(hexes[51744 + p * 2]),16),int(str(hexes[51753 + p * 2]) + str(hexes[51752 + p * 2]),16)])
    periodstats.append([games['away'],p + 1,int(str(hexes[52613 + p * 2]) + str(hexes[52612 + p * 2]),16),int(str(hexes[52621 + p * 2]) + str(hexes[52620 + p * 2]),16)])
mycursor.executemany(sql, periodstats)
mydb.commit()
# print('Period Stats successfully uploaded')

# get rosters from database
rosters = {}
for key, value in teams.items():
    mycursor.execute("SELECT * FROM players WHERE team_id = {} ORDER BY id ASC".format(teams[key][0]))
    rosters[key] = mycursor.fetchall()

# insert into scoringsummary table
scoringsummary = []
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
    else:
        scoresummary_team = 'away'
    goalscorer = rosters[scoresummary_team][int(hexes[i + 2],16)][0]

    if hexes[i + 5] != 'ff':
        assist1 = rosters[scoresummary_team][int(hexes[i + 5],16)][0]
    else:
        assist1 = None
    if hexes[i + 4] != 'ff':
        assist2 = rosters[scoresummary_team][int(hexes[i + 4],16)][0]
    else:
        assist2 = None
    scoringsummary.append([scheduleid,period,timeelapsed,goaltype,goalscorer,assist1,assist2])
# run SQL
sql = "INSERT INTO scoringsummary (schedule_id,period,timeelapsed,goaltype,goal_player_id,assist1_player_id,assist2_player_id) VALUES (%s, %s, %s, %s, %s, %s, %s)"
mycursor.executemany(sql, scoringsummary)
mydb.commit()
# print('Scoring Summary successfully uploaded')

# insert into penaltysummary table
penaltysummary = []
penasummsize = int(hexes[50668],16)
for i in range(50670, 50670 + penasummsize, 4):
    if hexes[i + 1][:1] == '0':
        period = 1
    elif hexes[i + 1][:1] == '4':
        period = 2
    elif hexes[i + 1][:1] == '8':
        period = 3
    else:
        period = 4
    timeelapsed = int(str(hexes[i + 1][1:]) + str(hexes[i]),16)
    penaltytype = int(hexes[i + 3],16)
    if penaltytype < 100:
        penaltysummary_team = 'home'
        penaltyid = penaltytype - 16
    else:
        penaltysummary_team = 'away'
        penaltyid = penaltytype - 144
    penaltyon = rosters[penaltysummary_team][int(hexes[i + 2],16)][0]
    penaltysummary.append([scheduleid,period,timeelapsed,penaltyid,penaltyon])
# run SQL
sql = "INSERT INTO penaltysummary (schedule_id,period,timeelapsed,penalty_id,player_id) VALUES (%s, %s, %s, %s, %s)"
mycursor.executemany(sql, penaltysummary)
mydb.commit()
# print('Penalty Summary successfully uploaded')

# insert into playerstats table
playerstats = {}
# home
players = []
for player_index, v in enumerate(rosters['home']):
    players.append([games['home'],rosters['home'][player_index][0],
        int(hexes[51089 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[51115 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[51141 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[51167 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[51193 + player_index + (player_index + 1) % 2 * 2],16),
        int(str(hexes[51219 + (player_index + 1) * 2]) + (hexes[51218 + (player_index + 1) * 2]),16)
    ])
playerstats['home'] = players
# away
players = []
for player_index, v in enumerate(rosters['away']):
    players.append([games['away'],rosters['away'][player_index][0],
        int(hexes[51957 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[51983 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[52009 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[52035 + player_index + (player_index + 1) % 2 * 2],16),
        int(hexes[52061 + player_index + (player_index + 1) % 2 * 2],16),
        int(str(hexes[52087 + (player_index + 1) * 2]) + (hexes[52086 + (player_index + 1) * 2]),16)
    ])
playerstats['away'] = players
# add +/-
plusminus = {}
# initialize all player containers
temph = []
tempa = []
for i in range(0, 25):
    temph.append(0)
    tempa.append(0)
plusminus['home'] = temph
plusminus['away'] = tempa
# parse data
pmsize = int(str(hexes[57093]) + str(hexes[57092]),16)
for i in range(57094, 57094 + pmsize, 14):
    if (hexes[i + 1][:1] == '0' or hexes[i + 1][:1] == '8') and int(hexes[i + 1][1:],16) <= 2:
        if hexes[i + 1][:1] == '0':
            scoringteam = 'home'
        elif hexes[i + 1][:1] == '8':
            scoringteam = 'away'
        for h in range(2,8):
            if int(hexes[i + h],16) != 255:
                plusminus['home'][int(hexes[i + h],16)] = plusminus['home'][int(hexes[i + h],16)] + (1 if scoringteam == 'home' else -1)
        for a in range(8,14):
            if int(hexes[i + a],16) != 255:
                plusminus['away'][int(hexes[i + a],16)] = plusminus['away'][int(hexes[i + a],16)] + (1 if scoringteam == 'away' else -1)
# apend +/- to playerstats
for key, val in playerstats.items():
    for player_index, val in enumerate(playerstats[key]):
        playerstats[key][player_index].append(plusminus[key][player_index])
# run SQL
sql = "INSERT INTO playerstats (game_id, player_id, goals, assists, sog, checksfor, checksagainst, toi, plusminus) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"
mycursor.executemany(sql, playerstats['home'])
mycursor.executemany(sql, playerstats['away'])
mydb.commit()
# print('Player Stats successfully uploaded')
