#include<stdio.h>
#include<stdlib.h>
#include<time.h>

void reset_string(char* string_val, const int STRING_VAL_SIZE){
	int lower = 97;
	int upper = 122;
	string_val[0] = '\"';
	string_val[STRING_VAL_SIZE-2] = '\"';
	string_val[STRING_VAL_SIZE-1] = '\0';
	for(int i = 1; i < STRING_VAL_SIZE-2; i++){
		string_val[i] = (rand() % (upper - lower + 1)) + lower;
	}
}


int main(int argc, char** argv){

	FILE* fp = fopen("insert_tokens.sql", "w");

	if(!fp){
		fprintf(stderr, "Error opening file\n");
		return 1;
	}

	const int STRING_VAL_SIZE = 64;
	char* string_val = (char*) malloc(STRING_VAL_SIZE*sizeof(char));
	srand(time(NULL));
	


	


	for(int i = 0; i < 100; i++){
		reset_string(string_val, STRING_VAL_SIZE);
		printf("%s\n", string_val);
		fprintf(fp, "INSERT INTO token VALUES (%s, NULL);\n", string_val); 
	}

	free(string_val);

	fclose(fp);

	return 0;
}
