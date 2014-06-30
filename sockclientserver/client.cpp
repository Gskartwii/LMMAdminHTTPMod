#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <netdb.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <sys/socket.h>
#include <string>
#include <arpa/inet.h>
#include <sys/ioctl.h>
#include "commands.h"

#define PORT "555" // the port client will be connecting to 
#define MAXDATASIZE 100 // max number of bytes we can get at once 

// get sockaddr, IPv4 or IPv6:
void* get_in_addr(struct sockaddr *sa)
{
    if (sa->sa_family == AF_INET) {
        return &(((struct sockaddr_in*)sa)->sin_addr);
    }

    return &(((struct sockaddr_in6*)sa)->sin6_addr);
}

int conn(const char* server) {
    int sockfd, numbytes;  
    struct addrinfo hints, *servinfo, *p;
    int rv;
    char s[INET6_ADDRSTRLEN];

    memset(&hints, 0, sizeof hints);
    hints.ai_family = AF_UNSPEC;
    hints.ai_socktype = SOCK_STREAM;

    if ((rv = getaddrinfo(server, PORT, &hints, &servinfo)) != 0) {
        fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));
        exit(1);
    }

    // loop through all the results and connect to the first we can
    for(p = servinfo; p != NULL; p = p->ai_next) {
        if ((sockfd = socket(p->ai_family, p->ai_socktype,
                p->ai_protocol)) == -1) {
            perror("client: socket");
            continue;
        }

        if (connect(sockfd, p->ai_addr, p->ai_addrlen) == -1) {
            close(sockfd);
            perror("client: connect");
            continue;
        }

        break;
    }

    if (p == NULL) {
        fprintf(stderr, "client: failed to connect\n");
        exit(2);
    }

    inet_ntop(p->ai_family, get_in_addr((struct sockaddr *)p->ai_addr),
            s, sizeof s);
    //printf("client: connecting to %s\n", s);

    freeaddrinfo(servinfo); // all done with this structure
    return sockfd;
}

std::string getnewlog(const char* server, int pid, int sid, const char* authcode) {
    int sockfd = conn(server);
    char buf[2];
    std::string buf2;
    char command[2000];
    sprintf(command, "%c%d%c%d%c%s%c\0", CMD_GETNEWLOG, pid, CMD_SEPERATE, sid, CMD_SEPERATE, authcode, CMD_END);

    if (send(sockfd, command, strlen(command), 0) == -1)
        fprintf(stderr, "send\n");
    while (recv(sockfd, buf, 1, 0))
        buf2 += std::string(buf);
    close(sockfd);
    return buf2;
}

std::string getservers(const char* server, int pid, int mode, const char* authcode) {
    int sockfd = conn(server);
    char buf[2];
    std::string buf2;
    char command[2000];
    sprintf(command, "%c%d%c%d%c%s%c", CMD_GETSERVERS, pid, CMD_SEPERATE, mode, CMD_SEPERATE, authcode, CMD_END);

    if (send(sockfd, command, strlen(command), 0) == -1)
        fprintf(stderr, "send\n");
    while (recv(sockfd, buf, 1, 0))
        buf2 += std::string(buf);
    close(sockfd);
    return buf2;
}

std::string getusers(const char* server, int pid, int sid, const char* authcode) {
    int sockfd = conn(server);
    char buf[2];
    std::string buf2;
    char command[2000];
    sprintf(command, "%c%d%c%d%c%s%c", CMD_GETUSERS, pid, CMD_SEPERATE, sid, CMD_SEPERATE, authcode, CMD_END);

    if (send(sockfd, command, strlen(command), 0) == -1)
        fprintf(stderr, "send\n");
    while (recv(sockfd, buf, 1, 0))
        buf2 += std::string(buf);
    close(sockfd);
    return buf2;
}
void printcommandline() {
    printf("\e[25;0H<");
}


const char* command_getlog    = "getlog";
const char* command_getserver = "getsid";
const char* command_getusers  = "getonline";
const char* command_track     = "track";

const char* mode_deadonly     = "dead\n\0";
const char* mode_aliveonly    = "alive\n\0";
const char* mode_all          = "all\n\0";

int main(int argc, char* argv[])
{
    if (argc < 3) {
        fprintf(stderr, "\e[31mUsage: %s <server> <command>\e[0m\n", argv[0]);
        exit(1);
    }
    
    if (!strcmp(argv[2], command_getlog)) {
        std::string newlog;
        char authcode[17];
        char pid[17];
        char sid[17];

        printf("Getting log...\n");
        printf("Enter your AuthCode:\n>");
        char input[17];
        fgets(input, 17, stdin);
        strcpy(authcode, input);
        authcode[strlen(authcode)-1] = '\0'; // It has a newline at the end of it that we need to get rid of
        //newlog = getnewlog(argv[1], 162636445, 1);
        printf("Enter your place id:\n>");
        fgets(input, 17, stdin);
        strcpy(pid, input);
        printf("Enter your server id (enter 0 for all servers):\n>");
        fgets(input, 17, stdin);
        strcpy(sid, input);

       // printf("Sending request...\nAuthCode = %s, pid = %s, sid = %s\n", authcode, pid, sid);
        newlog = getnewlog(argv[1], atol(pid), atol(sid), authcode);
        printf("New log: \n%s", newlog.c_str());
    }
    else if (!strcmp(argv[2], command_getserver)) {
        std::string sidlist;
        char authcode[17];
        char pid[17];
        int  mode;

        printf("Getting servers...\n");
        printf("Enter your AuthCode:\n>");
        char input[17];
        fgets(input, 17, stdin);
        strcpy(authcode, input);
        authcode[strlen(authcode)-1] = '\0';
        printf("Enter your place id:\n>");
        fgets(input, 17, stdin);
        strcpy(pid, input);
        printf("Mode?\ndead = dead servers only\nalive = alive servers only\nall = all servers\n>");
        fgets(input, 17, stdin);
        if (!strcmp(input, mode_deadonly))
            mode = 0;
        else if (!strcmp(input, mode_aliveonly))
            mode = 1;
        else if (!strcmp(input, mode_all))
            mode = 2;
        else {
            printf("UNKNOWN MODE %s!\n", input);
            exit(1);
        }

        sidlist = getservers(argv[1], atol(pid), mode, authcode);
        printf("Server list:\n%s", sidlist.c_str());
    }
    else if (!strcmp(argv[2], command_getusers)) {
        std::string userlist;
        char authcode[17];
        char pid[17];
        char sid[17];

        printf("Getting users...\n");
        printf("Enter your AuthCode:\n>");
        char input[17];
        fgets(input, 17, stdin);
        strcpy(authcode, input);
        authcode[strlen(authcode)-1] = '\0';
        printf("Enter your place id:\n>");
        fgets(input, 17, stdin);
        strcpy(pid, input);
        printf("Server id? 0 for all servers\n>");
        fgets(input, 17, stdin);
        strcpy(sid, input);

        userlist = getusers(argv[1], atol(pid), atol(sid), authcode);
        printf("Userlist: \n%s\e[0m", userlist.c_str());
    }
    else if (!strcmp(argv[2], command_track)) {
        char authcode[17];
        char pid[17];
        char sid[17];
        std::string newlog;

        printf("Entering tracking mode...\nCtrl+C to quit!\n");
        printf("Enter your AuthCode:\n>");
        char input[17];
        fgets(input, 17, stdin);
        strcpy(authcode, input);
        authcode[strlen(authcode)-1] = '\0';
        printf("Enter your place id:\n>");
        fgets(input, 17, stdin);
        strcpy(pid, input);
        printf("Enter your server id (0 for all servers):\n>");
        fgets(input, 17, stdin);
        strcpy(sid, input);
        printcommandline();
        while (1) {
            newlog = getnewlog(argv[1], atol(pid), atol(sid), authcode);
            if (strlen(newlog.c_str()))
                printcommandline();
            printf(newlog.c_str());
            newlog = std::string("");
            usleep(5000000);
        }
    }
    else
        fprintf(stderr, "UNKNOWN COMMAND %s\n", argv[2]);
    return 0;
}