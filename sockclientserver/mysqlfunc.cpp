#include <mysql/my_global.h>
#include <mysql/mysql.h>
#include <string.h>


void mysqlexit(MYSQL* con) {
	fprintf(stderr, "%s\n", mysql_error(con));
	mysql_close(con);
	exit(1);
}

int checkauthcode(MYSQL* con, int pid, std::string authcode) {
	printf("Checking authcode...\npid = %d, authcode = %s\n", pid, authcode.c_str());
	const char* query = "SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'roblox_placeids_%'";
	if (mysql_query(con, query))
		mysqlexit(con);
	std::string pc("");
	std::string pcun("");
	MYSQL_RES* result = mysql_store_result(con);
	if (result == NULL)
		mysqlexit(con);
	MYSQL_ROW row;
	char query2[2000];
	while ((row = mysql_fetch_row(result))) {
		std::string tblname = std::string(row[0]);
		sprintf(query2, "SELECT placecreator FROM %s WHERE placeid='%d' AND verified='1'", tblname.c_str(), pid);
		if (mysql_query(con, query2))
			mysqlexit(con);
		MYSQL_ROW row2;
		MYSQL_RES* result2 = mysql_store_result(con);
		if (result2 == NULL)
			mysqlexit(con);
		if ((row2 = mysql_fetch_row(result2))) {
			pc = std::string(row2[0]);
			pcun = tblname.substr(16);
		}
		mysql_free_result(result2);
	}
	mysql_free_result(result);
	if (pc.length() == 0) return 0;
	sprintf(query2, "SELECT * FROM roblox_codes WHERE un='%s' AND code='%s'", pcun.c_str(), authcode.c_str());
	if (mysql_query(con, query2))
		mysqlexit(con);
	MYSQL_RES* res = mysql_store_result(con);
	if (!mysql_num_rows(res)) {
		mysql_free_result(res);
		return 0;
	}
	mysql_free_result(res);
	return 1;
}

std::string getservers(MYSQL* con, int pid, int mode) {
	std::string ret;
	char query[2000];
	std::string tab("\t");
	std::string nl("\n");

	std::string status;
	char* sid;

	if (mode == 0)
		sprintf(query, "SELECT * FROM roblox_log_sid_%d WHERE status='dead'", pid);
	else if (mode == 1)
		sprintf(query, "SELECT * FROM roblox_log_sid_%d WHERE status='alive'", pid);
	else if (mode == 2)
		sprintf(query, "SELECT * FROM roblox_log_sid_%d", pid);

	if (mysql_query(con, query))
		mysqlexit(con);
	MYSQL_RES *result = mysql_store_result(con);
	if (result == NULL)
		mysqlexit(con);
	MYSQL_ROW row;
	while ((row = mysql_fetch_row(result))) { 
		status = std::string(row[1]);
		sid = row[2];
		ret += std::string("\e[33m");
		ret += std::string(sid);
		ret += tab;
		if (!status.compare("dead"))
			ret += std::string("\e[31mdead");
		else
			ret += std::string("\e[32malive");
		ret += std::string("\e[0m");
		ret += nl;
	}
	mysql_free_result(result);
	return ret;
}

std::string getusers(MYSQL* con, int pid, int sid) {
	std::string ret;
	std::string nl("\n");
	std::string tab("\t");

	std::string username;
	char* sid2;

	char query[2000];
	sprintf(query, "SELECT * FROM roblox_log_userlist_%d", pid);
	if (sid) {
		char buf[1000];
		sprintf(buf, " WHERE sid='%d'", sid);
		strcat(query, buf);
	}
	printf("Running query %s\n", query);
	if (mysql_query(con, query))
		mysqlexit(con);
	MYSQL_RES* result = mysql_store_result(con);
	if (result == NULL)
		mysqlexit(con);
	MYSQL_ROW row;
	while ((row = mysql_fetch_row(result))) {
		username = std::string(row[1]);
		sid2	 = row[2];
		ret += "\e[34m";
		ret += username;
		ret += tab + tab;
		ret += "\e[35m";
		ret += std::string(sid2);
		ret += nl;
	}
	mysql_free_result(result);
	//printf(ret.c_str());

	return ret;
}

std::string getnewlog(MYSQL* con, int pid, int sid) {
	std::string ret;
	const char* newline = "\n";
	char query[2000];

	std::string username;
	std::string message;
	//int timestamp;
	//char timestamp2[255];
	char* timestamp;
	char* sid2;
	//int sid2;
	//char sid3[255];
	//printf(query);

	sprintf(query, "SELECT * FROM roblox_log_%d WHERE new='1'", pid);
	/*sprintf(query, "%s%d", query, pid);
	strcat(query, " WHERE new='1'");*/
	if (sid) {
		char buf[1000];
		sprintf(buf, " AND sid='%d'", sid);
		strcat(query, buf);
	}
	//exit(1);
	if (mysql_query(con, query))
		mysqlexit(con);
	MYSQL_RES *result = mysql_store_result(con);
	if (result == NULL)
		mysqlexit(con);
	MYSQL_ROW row;
	while ((row = mysql_fetch_row(result))) { 
		username 	= std::string(row[1]);
		message 	= std::string(row[2]);
		timestamp 	= row[3];
		sid2 		= row[4];
		if (!username.compare(std::string("<i>SYSTEM</i>")))
			username = std::string("\e[32mSYSTEM\e[0m");
		ret += username;
	 	ret += std::string(": ");
	 	ret += message;
	 	ret += std::string(" (\e[35m");
	 	ret += timestamp;
	 	ret += std::string("\e[0m UNIX timestamp GMT, sid = \e[36m");
	 	ret += sid2;
	 	ret += std::string("\e[0m)");
	    ret += std::string(newline);
	}
	//printf("ret = %s\n", ret.c_str());
	sprintf(query, "UPDATE roblox_log_%d SET new='0' WHERE new='1'", pid);
	/*sprintf(query, "%s%d", query, pid);
	strcat(query, " WHERE new='1'");*/
	if (sid) {
		char buf[1000];
		sprintf(buf, " AND sid='%d'", sid);
		strcat(query, buf);
	}
	if (mysql_query(con, query))
		mysqlexit(con);
	mysql_free_result(result);

	return ret;
}