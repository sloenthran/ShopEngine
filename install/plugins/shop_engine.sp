#include <sourcemod>
#include <console>
#include <string>
#include <halflife>
#include <clients>

#pragma tabsize 0

new Handle:ServerHost = INVALID_HANDLE;
new	Handle:ServerID;

public Plugin:myinfo =
{
	
	name = "Shop Engine",
	author = "Sloenthran",
	description = "Shop Engine Core",
	version = "1.2",
	url = "http://sloenthran.pl"
	
};

public OnPluginStart()
{
	
	RegConsoleCmd("say /sklepsms"", OpenShop);
	RegConsoleCmd("say /konto"", OpenReservation);
	
	ServerHost = CreateConVar("shop_engine_host", "sloenthran.pl", "Shop URL");
	ServerID = CreateConVar("shop_engine_id", "1", "Shop Server ID");
	
}

public Action:OpenShop(User, Args)
{
	
	decl String:Host[128];
	decl String:Text[256];
	
	new ID = GetConVarInt(ServerID);
	
	GetConVarString(ServerHost, Host, 127);
	
	FormatEx(Text, 255, "http://%s/buy-%i-SERVER.html", Host, ID);
	
	ShowMOTDPanel(User, "Sloenthran :: Sklep SMS", Text, MOTDPANEL_TYPE_URL);
	
	return PLUGIN_HANDLED;
	
}

public Action:OpenReservation(User, Args)
{
	
	decl String:Host[128];
	decl String:Text[256];
	decl String:Name[64];
	
	new ID = GetConVarInt(ServerID);
	
	GetConVarString(ServerHost, Host, 127);
	
	GetClientName(User, Name, 63);
	
	FormatEx(Text, 255, "http://%s/server_reservation-%i-%s.html", Host, ID, Name);
	
	ShowMOTDPanel(User, "Sloenthran :: Rezerwacja nicku", Text, MOTDPANEL_TYPE_URL);
	
	return PLUGIN_HANDLED;
	
}