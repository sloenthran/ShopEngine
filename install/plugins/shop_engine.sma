#include <amxmodx>
#include <core>

#pragma tabsize 0

new CvarHost, ValueHost[64], CvarID, ValueID;

public plugin_init()
{
	
	register_plugin("Shop Engine", "1.6", "Sloenthran");
	
	register_clcmd("say /konto", "OpenReservation");
	register_clcmd("say_team /konto", "OpenReservation");
	
	register_clcmd("say /sklepsms", "OpenShop");
	register_clcmd("say_team /sklepsms", "OpenShop");
	
	CvarHost = register_cvar("shop_engine_host", "sloenthran.pl", FCVAR_PROTECTED|FCVAR_SPONLY);
	CvarID = register_cvar("shop_engine_id", "1", FCVAR_PROTECTED|FCVAR_SPONLY);
	
	set_task(60.0, "ReloadAdmins", .flags="b");
	
}

public plugin_cfg()
{
    
    get_pcvar_string(CvarHost, ValueHost, 63);
    ValueID  = get_pcvar_num(CvarID);

}

public OpenReservation(User)
{
    
    new Text[256], Name[64];
    
    get_user_name(User, Name, 63);
    
    formatex(Text, 255, "<html><head><meta http-equiv=^"REFRESH^" content=^"0; url=http://%s/server_reservation-%i-%s.html^"></head></html>", ValueHost, ValueID, Name);
    
    show_motd(User, Text, "Sloenthran :: Rezerwacja nicku");
    
    return PLUGIN_HANDLED;
    
}

public OpenShop(User)
{
    
    new Text[256];
    
    formatex(Text, 255, "<html><head><meta http-equiv=^"REFRESH^" content=^"0; url=http://%s/buy-%i-SERVER.html^"></head></html>", ValueHost, ValueID);
    
    show_motd(User, Text, "Sloenthran :: Sklep SMS");
    
    return PLUGIN_HANDLED;
    
}

public ReloadAdmins(User)
{
	
	server_cmd("amx_reloadadmins");
	
}