#!C:/Program Files/Python38/python.exe

import binascii
import csv

# open save file
filename = r'C:\Users\Aaron\AppData\Roaming\RetroArch\states\nhl94_updated.state'
with open(filename,'rb') as inputfile:
    content = inputfile.read()
    hexFile = binascii.hexlify(content).decode('utf-8')
    n = 2
    hexes = [(hexFile[i:i+n]) for i in range(0, len(hexFile), n)]

# check points
playerstats = {}
players = []
for h in range(0,24):
    players.append([int(hexes[51089 + h + (h + 1) % 2 * 2],16),
        int(hexes[51115 + h + (h + 1) % 2 * 2],16)
    ])
playerstats['home'] = players
players = []
for a in range(0,24):
    players.append([int(hexes[51957 + a + (a + 1) % 2 * 2],16),
        int(hexes[51983 + a + (a + 1) % 2 * 2],16)
    ])
playerstats['away'] = players

# parse scoringsummary
with open("www\scoringsummary.txt",'r') as goalfile:
    goal_list = csv.reader(goalfile,delimiter=',')
    for goal in goal_list:
        # goal scorer
        hexes[int(goal[0]) + 2] = format(int(goal[1]),'02x')
        # determine team for which set of player stats to update
        if hexes[int(goal[0]) + 3][:1] == '0':
            scoresummary_team = 'home'
        else:
            scoresummary_team = 'away'
        # player stats
        # check if assist1 is different
        if hexes[int(goal[0]) + 5] != format(int(goal[2]),'02x'):
            # check if the original assist was not empty
            if hexes[int(goal[0]) + 5] != 'ff':
                # if not, then reduce the original assist by one
                playerstats[scoresummary_team][int(hexes[int(goal[0]) + 5],16)][1] = int(playerstats[scoresummary_team][int(hexes[int(goal[0]) + 5],16)][1] - 1)
            # always increase the new assist by one
            playerstats[scoresummary_team][int(goal[2])][1] = int(playerstats[scoresummary_team][int(goal[2])][1] + 1)
        # print('original assist2={}'.format(hexes[int(goal[0]) + 4]))
        # print('new assist2={}'.format(format(int(goal[3]),'02x')))
        if hexes[int(goal[0]) + 4] != format(int(goal[3]),'02x'):
            if hexes[int(goal[0]) + 4] != 'ff':
                playerstats[scoresummary_team][int(hexes[int(goal[0]) + 4],16)][1] = int(playerstats[scoresummary_team][int(hexes[int(goal[0]) + 4],16)][1] - 1)
            playerstats[scoresummary_team][int(goal[3])][1] = int(playerstats[scoresummary_team][int(goal[3])][1] + 1)
        # scoring summary
        hexes[int(goal[0]) + 5] = 'ff' if goal[2] == '255' else format(int(goal[2]),'02x')
        hexes[int(goal[0]) + 4] = 'ff' if goal[3] == '255' else format(int(goal[3]),'02x')

# write back to hexes
for h in range(0,24):
    hexes[51115 + h + (h + 1) % 2 * 2] = format(playerstats['home'][h][1],'02x')
# print(hexes[51985])
for a in range(0,24):
    hexes[51983 + a + (a + 1) % 2 * 2] = format(playerstats['away'][a][1],'02x')
# print(hexes[51985])

newhexes = []
for i in range(0,len(hexes)):
    newhexes.append(int(str(hexes[i]),16))
switchedfile = bytearray(newhexes)
with open(filename,'wb') as writefile:
    writefile.write(switchedfile)
