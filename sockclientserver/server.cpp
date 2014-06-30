#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <string>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>
#include <arpa/inet.h>
#include <sys/wait.h>
#include <signal.h>
#include <mysql/my_global.h>
#include <mysql/mysql.h> // You won't believe how much pain getting this work was!

#include "mysql_globals.h"
#include "mysqlfunc.cpp"
#include "commands.h"
// Credits to beej.us
#define PORT "555"
#define BACKLOG 3

void sigchld_handler(int s) {
	while (waitpid(-1, NULL, WNOHANG) > 0);
}

void* get_in_addr(struct sockaddr *sa) {
	if (sa->sa_family == AF_INET)
		return &(
			(
				( // cast
					struct sockaddr_in*
				)
				sa
			)->sin_addr
		);

	return &(
		(
			(
				( // cast
					struct sockaddr_in6*
				)
				sa
			)
		)->sin6_addr
	);
}

int main(int argc, char* argv[]) {
	printf("Starting...\n");
	int sockfd, new_fd;
	struct addrinfo hints, *servinfo, *p;
	struct sockaddr_storage their_addr;
	socklen_t sin_size;
	struct sigaction sa;
	int yes = 1;
	char s[INET6_ADDRSTRLEN];
	int rv;

	memset(&hints, 0, sizeof hints);
	hints.ai_family = AF_UNSPEC;
	hints.ai_socktype = SOCK_STREAM;
	hints.ai_flags = AI_PASSIVE;
	if ((rv = getaddrinfo(NULL, PORT, &hints, &servinfo)) != 0) {
		fprintf(stderr, "\e[31mgetaddrinfo: %s\e[0m\n", gai_strerror(rv));
		return 1;
	}

	for (p = servinfo; p != NULL;p = p->ai_next) {
		if ((sockfd = socket(p->ai_family,p ->ai_socktype, p->ai_protocol)) == -1) {
			fprintf(stderr, "\e[31mserver: socket\e[0m\n");
			continue;
		}

		if (setsockopt(sockfd, SOL_SOCKET, SO_REUSEADDR, &yes, sizeof(int)) == -1) {
			fprintf(stderr, "\e[31msetsockopt\e[0m\n");
			exit(1);
		}

		if (bind(sockfd, p->ai_addr, p->ai_addrlen) == -1) {
			close(sockfd);
			fprintf(stderr, "\e[31mserver: bind\e[0m\n");
		}

		break;
	}

	if (p == NULL) {
		fprintf(stderr, "server: failed to bind\n");
		return 2;
	}

	freeaddrinfo(servinfo);

	if (listen(sockfd, BACKLOG) == -1) {
		fprintf(stderr, "listen\n");
		exit(1);
	}

	sa.sa_handler = sigchld_handler;
	sigemptyset(&sa.sa_mask);
	sa.sa_flags = SA_RESTART;
	if (sigaction(SIGCHLD, &sa, NULL) == -1) {
		fprintf(stderr, "sigaction\n");
		exit(1);
	}

	printf("Connection to local database...\n");
	MYSQL* con = mysql_init(NULL);
	if (con == NULL) {
		fprintf(stderr, "%s\n", mysql_error(con));
		exit(1);
	}
	if (mysql_real_connect(con, MYSQL_DB_ADDR, MYSQL_DB_USER, MYSQL_DB_PASS, MYSQL_DB_DATABASE, 0, NULL, 0) == NULL) {
		mysqlexit(con);
	}

	printf("Waiting for connetions...\n");

	while(1) {
		sin_size = sizeof their_addr;
		new_fd = accept(sockfd, (struct sockaddr*)&their_addr, &sin_size);
		if (new_fd == -1) {
			fprintf(stderr, "accept\n");
			continue;
		}

		inet_ntop(their_addr.ss_family, get_in_addr((struct sockaddr*)&their_addr), s, sizeof s);
		printf("Connected: %s\n", s);

		if (!fork()) {
			close(sockfd);
			//if (send(new_fd, "Hello world!", 13, 0) == -1)
				//fprintf(stderr, "send\n");
			char msg[2000];
			int succ = 0;
			succ = recv(new_fd, &msg, 2000, 0);
			if (!succ) {
				printf("ERROR: RECEIVED BYTES: %d\n", succ);
				exit(1);
			}

			int cmd = msg[0];
			std::string arguments[5];
			int currarg = 0;
			std::string currarg2;
			int i;
			for (i = 1; i < strlen(msg); i++) {
				if (msg[i] == CMD_SEPERATE) {
					arguments[currarg] = currarg2;
					++currarg;
					currarg2 = std::string("");
					//printf("Seperate... CURRARG = %d\n", currarg);
				}
				else if (msg[i] == CMD_END) {
					arguments[currarg] = currarg2;
					break;
				}
				else
					currarg2 += msg[i];
				//printf("%X\n", msg[i]);
			}

			/*if (cmd == CMD_GETNEWLOG) {
				std::string temp = getnewlog(con, 162636445, 1);
				const char* ret = temp.c_str();
				
				if (send(new_fd, ret, strlen(ret), 0) == -1)
					fprintf(stderr, "send\n");
			}*/
			for (i = 0; i < sizeof(arguments) / sizeof(arguments[0]); i++)
				printf("arg%d = %s ", i, arguments[i].c_str());

			if (cmd == CMD_GETNEWLOG) {
				if (
					!checkauthcode(
						con,
						atol(
							arguments[0].c_str(	
							)
						),
						arguments[2].c_str(
						)
					)
				) {
					if (send(new_fd, "Error: invalid AuthCode", strlen("Error: invalid AuthCode"), 0) == -1)
						fprintf(stderr, "send\n");
					close(new_fd);
					exit(0);
				}
				std::string temp = getnewlog(con, atol(arguments[0].c_str()), atol(arguments[1].c_str()));
				//const char* ret = temp.c_str();
				//printf(ret);
				
				if (send(new_fd, temp.c_str(), temp.length(), 0) == -1)
					fprintf(stderr, "send\n");
			}
			else if (cmd == CMD_GETSERVERS) {
				if (
					!checkauthcode(
						con,
						atol(
							arguments[0].c_str(
							)
						),
						arguments[2].c_str(
						)
					)
				) {
					if (send(new_fd, "Error: invalid AuthCode", strlen("Error: invalid AuthCode"), 0) == -1)
						fprintf(stderr, "send\n");
					close(new_fd);
					exit(0);
				}
				std::string temp = getservers(con, atol(arguments[0].c_str()), atol(arguments[1].c_str()));

				if (send(new_fd, temp.c_str(), temp.length(), 0) == -1)
					fprintf(stderr, "send\n");
			}
			else if (cmd == CMD_GETUSERS) {
				if (
					!checkauthcode(
						con,
						atol(
							arguments[0].c_str(
							)
						),
						arguments[2].c_str(
						)
					)
				) {
					if (send(new_fd, "Error: invalid AuthCode", strlen("Error: invalid AuthCode"), 0) == -1)
						fprintf(stderr, "send\n");
					close(new_fd);
					exit(0);
				}
				std::string temp = getusers(con, atol(arguments[0].c_str()), atol(arguments[1].c_str()));

				if (send(new_fd, temp.c_str(), temp.length(), 0) == -1)
					fprintf(stderr, "send\n");
			}
			else
				if (send(new_fd, "Error: invalid command", strlen("Error: invalid command"), 0) == -1)
					fprintf(stderr, "send\n");
			close(new_fd);
			exit(0);
		}
		else
			printf("asdf\n");

		close(new_fd);
	}
	return 0;
}